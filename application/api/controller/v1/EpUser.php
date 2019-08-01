<?php

namespace app\api\controller\v1;

use app\api\model\EpUserCertModel;
use app\api\model\EpUserEmGroupModel;
use app\api\model\EpUserLoginHistoryModel;
use app\api\model\EpUserModel;
use Sms;
use think\cache\driver\Redis;
use think\Request;
use Util\Check;
use Util\Util;

class EpUser extends EpUserBase
{
    public function sendSms()
    {
        $params = Request::instance()->request();
        $phone = Check::check($params['phone'] ?? '', 11, 11);

        $signName = '正步网络科技';
        $templateCode = 'SMS_171495133';
        $code = Util::generateVcode(6);

        $sms = new Sms();
        $result = $sms->send($phone, $signName, $templateCode, $code);

        if ($result['Code'] == 'OK') {

            $redis = new Redis();
            $redis->set($phone, $code, 300);

            Util::printResult($GLOBALS['ERROR_SUCCESS'], '验证码发送成功');
            exit;
        } else {
            Util::printResult(-1, '验证码发送失败');
            exit;
        }
    }

    public function login()
    {
        $params = Request::instance()->request();
        $phone = Check::check($params['phone'] ?? '', 11, 11);
        $vCode = Check::check($params['vCode'] ?? '');
//
//        if ($vCode == '') {
//            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
//            exit;
//        }

        $redis = new Redis();
        $verifyCode = $redis->get($phone);

        if ($vCode != $verifyCode) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '验证码错误');
            exit;
        }

        $epUserModel = new EpUserModel();

        if (!$epUserModel->checkPhoneExist($phone)) {
            $username = '正步_' . Util::generateRandomCode(6);
            $avatar = '/avatar/a1.png';
            $data = [
                'avatar' => $avatar,
                'name' => $username,
                'phone' => $phone,
                'createTime' => currentTime(),
                'updateTime' => currentTime()
            ];

            $insertRow = $epUserModel->save($data);

            if ($insertRow < 0) {
                util::printResult($GLOBALS['ERROR_REGISTER'], '注册失败');
                exit;
            }
        }

        $detail = $epUserModel->getByPhone($phone);
        $detailData = $detail->toArray();

        $userId = $detailData['id'];
        $username = $detailData['name'];
        $avatar = $detailData['avatar'];
        $isReview = $detailData['isReview']; // -1 未通过审核  0等待审核（默认）  1已提交等待审核  2通过审核
        $type = $detailData['type']; // 1企业 2个人

        $token = password_hash($userId . $phone, PASSWORD_DEFAULT);
        $id_token = $userId . '|' . $token . '|' . $isReview;

        $loginIp = $_SERVER["REMOTE_ADDR"];
        $loginTime = date('Y-m-d H:i:s', time());
        $loginOut = 0;

        $array = [
            'loginPhone' => $phone,
            'userId' => $userId,
            'token' => $token,
            'loginIp' => $loginIp,
            'loginTime' => $loginTime,
            'loginOut' => $loginOut
        ];
        $epUserLoginHistoryModel = new EpUserLoginHistoryModel();
        $result = $epUserLoginHistoryModel->save($array);

        if ($result > 0) {
            $arr = [
                'uid' => $userId,
                'avatar' => $avatar,
                'name' => $username,
                'phone' => $phone,
                'isReview' => $isReview,
                'type' => $type,
                'id_token' => $id_token
            ];

            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录失败');
            exit;
        }
    }

    public function checkLogin()
    {
        $params = Request::instance()->request();
        $id_token = $params['id_token'] ?? '';

        if (!$id_token || $id_token == '') {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '未登录');
            exit;
        }
        $data = explode('|', $id_token);
        if (count($data) != 3) {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录凭证不正确');
            exit;
        }
        $userId = $data[0];
        $token = $data[1];
        $isReview = $data[2];
        $epUserLoginHistoryModel = new EpUserLoginHistoryModel();
        $count = $epUserLoginHistoryModel->countByIdToken($userId, $token);
        if ($count <= 0) {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '未登录');
            exit;
        }
        $GLOBALS['userId'] = $userId;
        $GLOBALS['isReview'] = $isReview;
    }

    /**
     * 企业认证
     */
    public function epCertification()
    {
        $params = Request::instance()->request();
        $companyName = Check::check($params['companyName'] ?? ''); //公司名称
        $companyAddr = Check::check($params['companyAddr'] ?? ''); //公司地址
        $businessLic = Check::check($params['businessLic'] ?? ''); //营业执照
        $otherQuaLic = Check::check($params['otherQuaLic'] ?? ''); //其他资质证明

        $userId = $GLOBALS['userId'];

        $epUserCertModel = new EpUserCertModel();

        if ($epUserCertModel->checkCompanyNameExist($companyName)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '该公司已通过审核');
            exit;
        }

        if ($epUserCertModel->checkCertStatus($userId)) {
            Util::printResult($GLOBALS['ERROR_REVIEW_WAIT'], '正在审核中');
            exit;
        }

        if ($companyAddr == '' || $companyName == '' || $businessLic == '' || $otherQuaLic == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $arr = [
            'userId' => $userId,
            'type' => 1,
            'companyName' => $companyName,
            'companyAddr' => $companyAddr,
            'businessLic' => $businessLic,
            'otherQuaLic' => $otherQuaLic,
            'pass' => 0,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $id = $epUserCertModel->addCert($arr, $userId);

        if ($id > 0) {
            $data['certId'] = $id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }
    }

    /**
     * 个人认证
     */
    public function pCertification()
    {
        $params = Request::instance()->request();
        $companyName = Check::check($params['companyName'] ?? '');
        $username = Check::check($params['username'] ?? '');
        $phone = Check::check($params['phone'] ?? '', 1, 11);
        $idCard = Check::check($params['idCard'] ?? '', 1, 20);
        $idCardFrontPic = Check::check($params['idCardFrontPic'] ?? '');
        $idCardBackPic = Check::check($params['idCardBackPic'] ?? '');

        if ($companyName == '' || $username == '' || $phone == '' || $idCard == '' || $idCardFrontPic == '' || $idCardBackPic == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $userId = $GLOBALS['userId'];

        $epUserCertModel = new EpUserCertModel();

        if ($epUserCertModel->checkCertStatus($userId)) {
            Util::printResult($GLOBALS['ERROR_REVIEW_WAIT'], '正在审核中');
            exit;
        }

        if (!$epUserCertModel->checkCompanyNameExist($companyName)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '公司不存在');
            exit;
        }

        $epUserModel = new EpUserModel();
        $detail = $epUserModel->getDetailByCompanyName($companyName);
//        var_dump($detail);
        $epId = $detail['id'];

        $arr = [
            'userId' => $userId,
            'epId' => $epId,
            'type' => 2,
            'username' => $username,
            'companyName' => $companyName,
            'phone' => $phone,
            'idCard' => $idCard,
            'idCardFrontPic' => $idCardFrontPic,
            'idCardBackPic' => $idCardBackPic,
            'pass' => 0,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];
        $id = $epUserCertModel->addCert($arr, $userId);

        if ($id > 0) {
            $data['certId'] = $id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }
    }

    /**
     * 获取企业的员工列表
     */
    public function getEmApplyListByEpUserId()
    {
        $epUserId = $GLOBALS['userId'];
        $epUserModel = new EpUserModel();
        $type = $epUserModel->verifyUserType($epUserId);
        if ($type != 1) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '不是企业用户');
            exit;
        }
        $epUserCertModel = new EpUserCertModel();
        $res = $epUserCertModel->getEmApplyListByEpId($epUserId);
        $data['list'] = $res;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    /**
     * 企业审核员工
     */
    public function review()
    {
        $params = Request::instance()->request();
        $certId = Check::checkInteger($params['certId'] ?? '');
        $pass = Check::checkInteger($params['pass'] ?? ''); // -1 不通过 1通过 0待审核
        $epUserId = $GLOBALS['userId'];

        $epUserModel = new EpUserModel();
        $type = $epUserModel->verifyUserType($epUserId);
        if ($type != 1) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '不是企业用户');
            exit;
        }
        $epUserCertModel = new EpUserCertModel();
        $detail = $epUserCertModel->getDetail($certId);
        if (empty($detail)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '参数错误');
            exit;
        }
        $epUserId = $detail['userId'];
        $companyName = $detail['companyName'];
        $type = $detail['type'];

        $updateRow = $epUserCertModel->updateCertStatus($certId, $pass, $epUserId, $companyName, $type);
        if ($updateRow > 0) {
            $arr['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '更新失败');
            exit;
        }
    }

    /**
     * 企业用户创建员工分组
     */
    public function addEmGroup()
    {
        $params = Request::instance()->request();
        $groupName = Check::check($params['groupName'] ?? ''); //组名
        $epUserId = $GLOBALS['userId'];

        $epUserModel = new EpUserModel();
        $type = $epUserModel->verifyUserType($epUserId);
        if ($type != 1) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '不是企业用户');
            exit;
        }

        $epUserEmGroupModel = new EpUserEmGroupModel();
        $data = [
            'epUserId' => $epUserId,
            'name' => $groupName,
            'createTime' => currentTime(),
            'createBy' => $epUserId,
            'updateTime' => currentTime(),
            'updateBy' => $epUserId
        ];

        $groupId = $epUserEmGroupModel->insertGetId($data, true, 'groupId');
        $arr['groupId'] = $groupId;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    /**
     * 企业用户编辑组别
     */
    public function editEmGroup()
    {
        $params = Request::instance()->request();
        $groupId = Check::checkInteger($params['groupId'] ?? ''); //组别id
        $groupName = Check::check($params['groupName'] ?? ''); //组名
        $epUserId = $GLOBALS['userId'];
        $epUserEmGroupModel = new EpUserEmGroupModel();
        if (!$epUserEmGroupModel->verifyCreatePermission($groupId, $epUserId)) {
            Util::printResult($GLOBALS['ERROR_PERMISSION'], '权限错误');
            exit;
        }
        $data = [
            'groupId' => $groupId,
            'epUserId' => $epUserId,
            'name' => $groupName,
            'updateTime' => currentTime(),
            'updateBy' => $epUserId
        ];
        $updateRow = $epUserEmGroupModel->isUpdate(true)->save($data);
        $arr['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    /**
     * 企业用户删除组别
     */
    public function delEmGroup()
    {
        $params = Request::instance()->request();
        $groupId = Check::checkInteger($params['groupId'] ?? ''); //组别id
        $epUserId = $GLOBALS['userId'];

        $epUserEmGroupModel = new EpUserEmGroupModel();
        if (!$epUserEmGroupModel->verifyCreatePermission($groupId, $epUserId)) {
            Util::printResult($GLOBALS['ERROR_PERMISSION'], '权限错误');
            exit;
        }
        $data = [
            'groupId' => $groupId,
            'isDelete' => 1,
            'updateTime' => currentTime(),
            'updateBy' => $epUserId
        ];
        $delRow = $epUserEmGroupModel->isUpdate(true)->save($data);
        $arr['delRow'] = $delRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    /**
     * 企业用户获取组别列表
     */
    public function getEmGroupListByEpUserId()
    {
        $epUserId = $GLOBALS['userId'];
        $epUserModel = new EpUserModel();
        $type = $epUserModel->verifyUserType($epUserId);
        if ($type != 1) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '不是企业用户');
            exit;
        }

        $epUserEmGroupModel = new EpUserEmGroupModel();

        $res = $epUserEmGroupModel->getAllByEpUserId($epUserId);
        $data['list'] = $res;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 企业用户修改员工的分组
     * @throws \think\Exception
     */
    public function changeEmGroupByEpUser()
    {
        $params = Request::instance()->request();
        //$emUserId = Check::checkInteger($params['emUserId'] ?? ''); // 员工id
        $emCertId = Check::checkInteger($params['emCertId'] ?? ''); //员工认证id
        $groupId = Check::checkInteger($params['groupId'] ?? ''); //组别id

        $epUserId = $GLOBALS['userId'];

        $epUserCertModel = new EpUserCertModel();
        if (!$epUserCertModel->verifyEmCertIdAndEpUserId($emCertId, $epUserId)) {
            Util::printResult($GLOBALS['ERROR_PERMISSION'], '权限错误');
            exit;
        }

        $updateRow = $epUserCertModel->updateGroupIdByEpUser($emCertId, $epUserId, $groupId);
        $data['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}
