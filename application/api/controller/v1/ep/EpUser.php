<?php

namespace app\api\controller\v1\ep;

use app\api\model\CompanyManagementModel;
use app\api\model\EpCertModel;
use app\api\model\EpOrderApplyModel;
use app\api\model\EpOrderModel;
use app\api\model\EpResumeCateModel;
use app\api\model\EpResumeModel;
use app\api\model\EpUserCert;
use app\api\model\EpUserCertModel;
use app\api\model\EpUserEmGroupModel;
use app\api\model\EpUserLoginHistoryModel;
use app\api\model\EpUserModel;
use Curl\Curl;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use OSS\Core\OssException;
use OSS\OssClient;
use Sms;
use think\cache\driver\Redis;
use think\Exception;
use think\exception\PDOException;
use think\Request;
use Util\Check;
use Util\RedisX;
use Util\Util;

class EpUser extends EpUserBase
{
    /**
     * 发送验证码
     */
    public function sendSms()
    {
        $params = Request::instance()->param();
        $phone = Check::check($params['phone'] ?? '', 11, 11);

        $signName = '正步网络科技';
        $templateCode = 'SMS_171495133';
        $code = Util::generateVcode(6);

        $sms = new Sms();
        $result = $sms->send($phone, $signName, $templateCode, $code);

        if ($result['Code'] == 'OK') {

//            $redis = new Redis();
            $redis = RedisX::instance();
            $redis->set($phone, $code, 300);

            Util::printResult($GLOBALS['ERROR_SUCCESS'], '验证码发送成功');
            exit;
        } else {
            Util::printResult(-1, '验证码发送失败');
            exit;
        }
    }

    /**
     * 用户登录
     * @throws Exception
     */
    public function login()
    {
        $params = Request::instance()->param();
        $phone = Check::check($params['phone'] ?? '', 11, 11);
        $vCode = Check::check($params['vCode'] ?? '');

//        if ($vCode == '') {
//            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
//            exit;
//        }

//        $redis = new Redis();
//        $redis = RedisX::instance();
//        $verifyCode = $redis->get($phone);
//
//        if ($vCode != $verifyCode) {
//            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '验证码错误');
//            exit;
//        }

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

    /**
     * 检查用户是否登录
     */
    public function checkLogin()
    {
        $params = Request::instance()->param();
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
     * 企业认证审核 添加
     */
    public function epCertification()
    {
        $params = Request::instance()->param();
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

        if ($realname == '' || $realphone == '' || $idCard == '' || $idCardFrontPic == '' || $idCardBackPic == ''
            || $companyAddr == '' || $companyName == '' || $businessLic == '' || $otherQuaLic == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $epUserCertModel = new EpUserCertModel();

        if ($epUserCertModel->checkEpHasCert($companyName)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '该公司已经通过审核');
            exit;
        }

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
        $params = Request::instance()->param();
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
        $params = Request::instance()->param();
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
        $realname = $detail['realname'];

        $res = $epUserCertModel->reviewEmByEp($certId, $pass, $emUserId, $companyName, $type,$realname);
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
     * 获取员工详情
     */
    public function getEmUserDetail()
    {
        $params = Request::instance()->param();
        $orderDate = $params['orderDate'] ?? ''; //筛选订单时间 格式2019-08
        $isFinish = $params['isFinish'] ?? 1; //默认已完成
        $userId = Check::checkInteger($params['userId'] ?? ''); //员工id

        if ($orderDate == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $orderModel = new EpOrderModel();

        $x = explode('-', $orderDate);
        $recOrderYear = $x[0];
        $recOrderMonth = $x[1];

        $list = $orderModel->getOrderListWithOrderDateWithEmUser($userId, $isFinish, $recOrderYear, $recOrderMonth);
        list($entryNumMonth, $incomeMonth, $orderNumMonth, $incomeTotal, $orderNum) = $orderModel->getOrderInfoByMonthWithEmUser($recOrderYear, $recOrderMonth, $userId, $isFinish);

        $data['incomeTotal'] = $incomeTotal;
        $data['orderNum'] = $orderNum;
        $data['orderNumMonth'] = $orderNumMonth;
        $data['entryNumMonth'] = $entryNumMonth;
        $data['incomeMonth'] = $incomeMonth;
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

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
        $x = $res->toArray();
        $epOrderModel = new EpOrderModel();
        foreach ($x as $k => $v) {
            $userId = $v['userId'];
            list($entryNumMonth, $incomeMonth, $orderNumMonth) = $epOrderModel->getOrderInfoByMonthWithEmUserNowMonth($userId);
            $x[$k]['orderNumMonth'] = $orderNumMonth;
            $x[$k]['entryNumMonth'] = $entryNumMonth;
            $x[$k]['incomeMonth'] = $incomeMonth;

            if ($v['groupId'] == 0) {
                $x[$k]['groupName'] = '未分组';
            }
        }
        $data['list'] = $x;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 根据组别获取员工列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEmListByGroupId()
    {
        $params = Request::instance()->param();
        $groupId = Check::checkInteger($params['groupId'] ?? 0); //groupId是-1获取全部的  groupId是-2获取待审核的
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $epUserId = $GLOBALS['userId'];
        $epUserModel = new EpUserModel();
        $type = $epUserModel->verifyUserType($epUserId);
        if ($type != 1) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '不是企业用户');
            exit;
        }
        $epCertModel = new EpCertModel();
        $epUserCertModel = new EpUserCertModel();
        $res = $epCertModel->getByUserIdAndType($epUserId, 1);
        $resData = $res->toArray();
        $epId = $resData['epId'];

        if ($groupId == -1) {
            $res = $epUserCertModel->getEmApplyListByEpIdPage($epId, $pageIndex, $pageSize);
            $x = $res->toArray();
        } else if ($groupId == -2) {
            $res = $epUserCertModel->getReviewEmApplyListByEpIdPage($epId, $pageIndex, $pageSize);
            $x = $res->toArray();
        } else {
            $res = $epUserCertModel->getEmApplyListByGroupIdPage($epId, $groupId, $pageIndex, $pageSize);
            $x = $res->toArray();
        }

        $epOrderModel = new EpOrderModel();
        foreach ($x['data'] as $k => $v) {
            $userId = $v['userId'];
            list($entryNumMonth, $incomeMonth, $orderNumMonth) = $epOrderModel->getOrderInfoByMonthWithEmUserNowMonth($userId);
            $x['data'][$k]['orderNumMonth'] = $orderNumMonth;
            $x['data'][$k]['entryNumMonth'] = $entryNumMonth;
            $x['data'][$k]['incomeMonth'] = $incomeMonth;

            if ($v['groupId'] == 0) {
                if ($v['pass'] == 1) {
                    $x['data'][$k]['groupName'] = '未分组';
                }
                if ($v['pass'] == 0) {
                    $x['data'][$k]['groupName'] = '待审核';
                }
            }
        }
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $x);
    }


    /**
     * 企业用户创建员工分组
     */
    public function addEmGroup()
    {
        $params = Request::instance()->param();
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
        $params = Request::instance()->param();
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
        $params = Request::instance()->param();
        $groupId = Check::checkInteger($params['groupId'] ?? ''); //组别id
        $epUserId = $GLOBALS['userId'];

        $epUserEmGroupModel = new EpUserEmGroupModel();
        if (!$epUserEmGroupModel->verifyCreatePermission($groupId, $epUserId)) {
            Util::printResult($GLOBALS['ERROR_PERMISSION'], '权限错误');
            exit;
        }
        $delRow = $epUserEmGroupModel->delGroup($groupId);

        if ($delRow > 0) {
            $arr['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }
    }

    /**
     * 企业用户删除员工
     * @throws PDOException
     */
    public function delEmUser()
    {
        $params = Request::instance()->param();
        $emUserId = Check::checkInteger($params['emUserId'] ?? '');

        $epUserId = $GLOBALS['userId'];

        $epUserModel = new EpUserModel();
        $type = $epUserModel->verifyUserType($epUserId);
        if ($type != 1) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '不是企业用户');
            exit;
        }

        $epUserModel->startTrans();
        try {
            $d1 = $epUserModel->delEmUser($emUserId); //更新员工用户状态为未认证状态
            if ($d1 == 0){
                $epUserModel->rollback();
                Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
                exit;
            }

            $epResumeModel = new EpResumeModel();
            $d2 = $epResumeModel->delEmUserResumeInfo($emUserId);

            $epResumeCateModel = new EpResumeCateModel();
            $d2 = $epResumeCateModel->delEmUserResumeCateInfo($emUserId);

            $epOrderModel = new EpOrderModel();
            $d3 = $epOrderModel->delEmUserOrderInfo($emUserId);

            $epOrderApplyModel = new EpOrderApplyModel();
            $d4 = $epOrderApplyModel->delEmUserOrderApplyInfo($emUserId);

            $epReviewModel = new  EpUserCertModel();
            $d5 = $epReviewModel->delEmUserReviewInfo($emUserId);
            if ($d5 == 0){
                $epUserModel->rollback();
                Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
                exit;
            }

            $epCertModel = new EpCertModel();
            $d6 = $epCertModel->delEmUserCertInfo($emUserId);

            $epUserModel->commit();
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '删除成功');
            exit;

        } catch (PDOException $e) {
            $epUserModel->rollback();
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }
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
        $epCertModel = new EpCertModel();
        $epUserCertModel = new EpUserCertModel();
        $res = $epCertModel->getByUserIdAndType($epUserId, 1);
        $resData = $res->toArray();
        $epId = $resData['epId'];

        $emNum = $epUserCertModel->getEmNumByEpId($epId, 1);
        $reviewNum = $epUserCertModel->getEmNumByEpId($epId, 2);

        $epUserEmGroupModel = new EpUserEmGroupModel();
        $res = $epUserEmGroupModel->getAllByEpUserId($epUserId);
        $data['emNum'] = $emNum;
        $data['reviewNum'] = $reviewNum;
        $data['list'] = $res;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 企业用户修改员工的分组
     * @throws \think\Exception
     */
    public function changeEmGroupByEpUser()
    {
        $params = Request::instance()->param();
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
     * 接单
     * @throws Exception
     * @throws \ErrorException
     */
    public function receiveOrder()
    {
        $params = Request::instance()->param();
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $userId = $GLOBALS['userId'];

        $epUserModel = new EpUserModel();
        $userInfo = $epUserModel->getUserInfo($userId);
        $userData = $userInfo->toArray();
        if ($userData['isReview'] != 2) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户未认证,不可接单');
            exit;
        }

        $epId = $userData['epId'];

        $epOrderModel = new EpOrderModel();
        if ($epOrderModel->checkUserRecOrder($positionId, $userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户已经接过该单');
            exit;
        }
        $orderId = get_order_sn();
        try {
            $curl = new Curl();
            $x = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $GLOBALS['mini_appid'] . '&secret=' . $GLOBALS['mini_secret'];
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            $curl->setHeader('Content-Type', 'application/json;charset=UTF-8');
            $curl->get($x);
            $xRes = $curl->response;
            $access_token = $xRes->access_token;

            $xx = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
            $scene = "$userId&$positionId&$orderId";
            $page = "pages/index/index";

            $curl->post($xx, ['scene' => $scene, 'page' => $page]);
            $xxRes = $curl->response;
            $object = 'mini_' . $orderId . '.png';

            $ossClient = new OssClient($GLOBALS['ACCESS_KEY_ID'], $GLOBALS['ACCESS_KEY_SECRET'], $GLOBALS['ENDPOINT']);
            $xll = $ossClient->putObject($GLOBALS['BUCKET'], $object, $xxRes);

            $saveUrl = 'https://' . $GLOBALS['PIC_SERVER'] . '/' . $object;
            $arr = [
                'orderId' => $orderId,
                'userId' => $userId,
                'epId' => $epId,
                'positionId' => $positionId,
                'qrCode' => $saveUrl,
                'recOrderYear' => date('Y', time()),
                'recOrderMonth' => date('m', time()),
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
        } catch (OssException $e) {
            Util::printResult($e->getCode(), $e->getMessage());
            exit;
        }
    }

}
