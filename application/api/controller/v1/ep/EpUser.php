<?php

namespace app\api\controller\v1\ep;

use app\api\model\CompanyManagementModel;
use app\api\model\EpCertModel;
use app\api\model\EpOrderModel;
use app\api\model\EpUserCertModel;
use app\api\model\EpUserEmGroupModel;
use app\api\model\EpUserLoginHistoryModel;
use app\api\model\EpUserModel;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Sms;
use think\cache\driver\Redis;
use think\Exception;
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
     * 添加企业认证审核
     */
    public function epCertification()
    {
        $params = Request::instance()->request();
        $realname = Check::check($params['realname'] ?? '');
        $realphone = Check::check($params['realphone'] ?? '', 1, 11);
        $idCard = Check::check($params['idCard'] ?? '', 1, 20);
        $idCardFrontPic = Check::check($params['idCardFrontPic'] ?? '');
        $idCardBackPic = Check::check($params['idCardBackPic'] ?? '');
        $companyName = Check::check($params['companyName'] ?? ''); //公司名称
        $companyAddr = Check::check($params['companyAddr'] ?? ''); //公司地址
        $businessLic = Check::check($params['businessLic'] ?? ''); //营业执照
        $otherQuaLic = Check::check($params['otherQuaLic'] ?? ''); //其他资质证明

        $userId = $GLOBALS['userId'];

        if ($realname == '' || $realphone == '' || $idCard == '' || $idCardFrontPic == '' || $idCardBackPic == '' || $companyAddr == '' || $companyName == '' || $businessLic == '' || $otherQuaLic == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $companyModel = new CompanyManagementModel();
        if ($companyModel->getDetailByCompanyName($companyName) != null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '该公司已经通过审核');
            exit;
        }

        $epUserCertModel = new EpUserCertModel();

        if ($epUserCertModel->verifyByCompanyNameAndUserId($companyName, $userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '正在审核');
            exit;
        }

        $arr = [
            'userId' => $userId,
            'type' => 1,
            'realname' => $realname,
            'realphone' => $realphone,
            'idCard' => $idCard,
            'idCardFrontPic' => $idCardFrontPic,
            'idCardBackPic' => $idCardBackPic,
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
        $realname = Check::check($params['realname'] ?? '');
        $realphone = Check::check($params['realphone'] ?? '', 1, 11);
        $idCard = Check::check($params['idCard'] ?? '', 1, 20);
        $idCardFrontPic = Check::check($params['idCardFrontPic'] ?? '');
        $idCardBackPic = Check::check($params['idCardBackPic'] ?? '');

        if ($companyName == '' || $realname == '' || $realphone == '' || $idCard == '' || $idCardFrontPic == '' || $idCardBackPic == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $userId = $GLOBALS['userId'];

        $epUserCertModel = new EpUserCertModel();
        if (!$epUserCertModel->checkEpHasCert($companyName)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '该企业未审核通过');
            exit;
        }

        if ($epUserCertModel->verifyByCompanyNameAndUserId($companyName, $userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '正在审核');
            exit;
        }

        if ($epUserCertModel->verifyCompanyNameAndUserId($companyName, $userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '已经通过审核');
            exit;
        }

        $companyModel = new CompanyManagementModel();
        $companyRes = $companyModel->getDetailByCompanyName($companyName);
        $companyData = $companyRes->toArray();
        $companyId = $companyData['id'];

        $arr = [
            'userId' => $userId,
            'applyEpId' => $companyId,
            'type' => 2,
            'realname' => $realname,
            'companyName' => $companyName,
            'realphone' => $realphone,
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
     * 企业审核员工
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function review()
    {
        $params = Request::instance()->request();
        $certId = Check::checkInteger($params['certId'] ?? ''); //审核的id
        $pass = Check::check($params['pass'] ?? ''); // 1通过 -1 拒绝
//        $epUserId = $GLOBALS['userId'];
        if ($pass == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $epUserCertModel = new EpUserCertModel();

        $res = $epUserCertModel->getDetail($certId);
        if ($res == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '参数错误');
            exit;
        }
        $detail = $res->toArray();

        $emUserId = $detail['userId'];
        $companyName = $detail['companyName'];
        $type = $detail['type'];

        $res = $epUserCertModel->reviewEmByEp($certId, $pass, $emUserId,$companyName, $type);
        if ($res > 0) {
            $arr['updateRow'] = $res;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '更新失败');
            exit;
        }
    }

    /**
     * 获取企业的员工列表
     */
    public function getEmListByEpUserId()
    {
        $epUserId = $GLOBALS['userId'];
        $epUserModel = new EpUserModel();
        $type = $epUserModel->verifyUserType($epUserId);
        if ($type != 1) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '不是企业用户');
            exit;
        }

        $epCertModel = new EpCertModel();
        $res = $epCertModel->getByUserIdAndType($epUserId, 1);
        $resData = $res->toArray();
        $epId = $resData['epId'];

        $epUserCertModel = new EpUserCertModel();
        $res = $epUserCertModel->getEmApplyListByEpId($epId);
        $data['list'] = $res;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
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
        $certId = Check::checkInteger($params['certId'] ?? ''); //员工认证id
        $groupId = Check::checkInteger($params['groupId'] ?? ''); //组别id

        $epUserId = $GLOBALS['userId'];
        $epUserModel = new EpUserModel();
        $type = $epUserModel->verifyUserType($epUserId);
        if ($type != 1) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '不是企业用户');
            exit;
        }

        $epCertModel = new EpCertModel();
        $res = $epCertModel->getByUserIdAndType($epUserId, 1);
        $resData = $res->toArray();
        $epId = $resData['epId'];

        $epUserCertModel = new EpUserCertModel();
        if (!$epUserCertModel->verifyApplyEpIdAndCertId($epId, $certId)) {
            Util::printResult($GLOBALS['ERROR_PERMISSION'], '权限错误');
            exit;
        }

        $updateRow = $epUserCertModel->updateGroupIdByEpUser($certId, $epUserId, $groupId);
        $data['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    /**
     * 接单 企业用户 普通用户都可以接单
     */
    public function receiveOrder()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $userId = $GLOBALS['userId'];
        $epOrderModel = new EpOrderModel();
        if ($epOrderModel->checkUserRecOrder($positionId, $userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户已经接过该单');
            exit;
        }
        $orderId = get_order_sn();
        try {
            $json = json_encode(
                [
                    'userId' => $userId,
                    'positionId' => $positionId,
                    'orderId' => $orderId
                ]
            );
            //生成二维码
            $qrCode = new QrCode($json);
            $qrCode->setSize(300);
            $qrCode->setWriterByName('png');
            $qrCode->setMargin(10);
            $qrCode->setEncoding('UTF-8');
            $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
            $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
            $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
            $qrCode->setLabel('扫描二维码报名', 12);
            $qrCode->setLogoPath(ROOT_PATH . 'public/avatar/a1.png');
            $qrCode->setLogoSize(50, 50);
            $qrCode->setRoundBlockSize(true);
            $qrCode->setValidateResult(false);
            $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
//            header('Content-Type: ' . $qrCode->getContentType());
            // Save it to a file
            $qrCodeUrl = ROOT_PATH . 'public/order/' . $orderId . '.png';
            $saveUrl = '/order/' . $orderId . '.png';
            $qrCode->writeFile($qrCodeUrl);

            $arr = [
                'orderId' => $orderId,
                'userId' => $userId,
                'positionId' => $positionId,
                'qrCode' => $saveUrl,
                'createBy' => $userId,
                'createTime' => currentTime(),
                'updateBy' => $userId,
                'updateTime' => currentTime()
            ];
            $recId = $epOrderModel->add($arr);
            if ($recId > 0) {
                $data['recId'] = $recId;
                $data['orderId'] = $orderId;
                $data['qrCode'] = $saveUrl;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            }
        } catch (Exception $e) {
            Util::printResult($GLOBALS['ERROR_EXCEPTION'], '出现异常');
            exit;
        }

    }

    /**
     * 根据订单id获取详情
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrderDetailByOrderId()
    {
        $params = Request::instance()->request();
        $orderId = Check::check($params['orderId'] ?? ''); //订单id
        $epOrderModel = new EpOrderModel();
        $detail = $epOrderModel->getDetailByOrderId($orderId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     *  分页获取用户接单列表
     */
    public function getUserRecOrdersPage()
    {
        $params = Request::instance()->request();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);
        $userId = $GLOBALS['userId'];

        $epOrderModel = new EpOrderModel();
        $page = $epOrderModel->getUserRecOrdersPage($userId, $pageIndex, $pageSize);
        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}
