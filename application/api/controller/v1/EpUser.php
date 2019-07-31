<?php

namespace app\api\controller\v1;

use app\api\model\EpUserCertModel;
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
        $isReview = $detailData['isReview'];

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
     * 认证
     */
    public function certification()
    {
        $params = Request::instance()->request();
        $type = $params['type'] ?? ''; // 1企业 2个人

        $userId = $GLOBALS['userId'];
        $isReview = $GLOBALS['isReview'];

        if ($isReview == -1) {
            Util::printResult($GLOBALS['ERROR_REVIEW_REFUSE'], '未通过审核');
            exit;
        }

        if ($isReview == 1) {
            Util::printResult($GLOBALS['ERROR_REVIEW_WAIT_HAVE'], '已经提交审核');
            exit;
        }

        if ($isReview == 2) {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], '审核已经通过');
            exit;
        }

        $epUserCertModel = new EpUserCertModel();

        if ($epUserCertModel->checkCertStatus($userId)){
            Util::printResult($GLOBALS['ERROR_REVIEW_WAIT'], '正在审核中');
            exit;
        }

        if ($type == 1) {
            $companyName = Check::check($params['companyName'] ?? ''); //公司名称
            $companyAddr = Check::check($params['companyAddr'] ?? ''); //公司地址
            $businessLic = Check::check($params['businessLic'] ?? ''); //营业执照
            $otherQuaLic = Check::check($params['otherQuaLic'] ?? ''); //其他资质证明

            if ($companyAddr == '' || $companyName == '' || $businessLic == '' || $otherQuaLic == '') {
                Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
                exit;
            }

            $arr = [
                'userId' => $userId,
                'type' => $type,
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

            $id = $epUserCertModel->addCert($arr, $type, $userId);

            if ($id > 0) {
                $data['certId'] = $id;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
                exit;
            }

        }

        if ($type == 2) {
            $companyName = Check::check($params['companyName'] ?? '');
            $username = Check::check($params['username'] ?? '');
            $phone = Check::check($params['phone'] ?? '', 1, 11);
            $idCard = Check::check($params['idCard'] ?? '',1,20);
            $idCardFrontPic = Check::check($params['idCardFrontPic'] ?? '');
            $idCardBackPic = Check::check($params['idCardBackPic'] ?? '');
            if ($companyName == '' || $username == '' || $phone == '' || $idCard == '' || $idCardFrontPic == '' || $idCardBackPic == '') {
                Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
                exit;
            }

            $arr = [
                'userId' => $userId,
                'type' => $type,
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
            $id = $epUserCertModel->addCert($arr, $type, $userId);

            if ($id > 0) {
                $data['certId'] = $id;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
                exit;
            }
        }
    }


}
