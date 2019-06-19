<?php

namespace app\api\controller\v1;

use app\api\model\UserLoginHistoryModel;
use app\api\model\UserModel;
use Sms;
use think\Controller;
use think\Request;
use think\Session;
use Util\Check;
use Util\Util;

class User extends Controller
{
    public function sendSms()
    {
        $params = Request::instance()->request();
        $phone = Check::check($params['phone'] ?? '', 11, 11);
//        $phone = '17621732412';
        $signName = '正步网络科技';
        $templateCode = 'SMS_164030786';
        $code = Util::generateVcode(6);

        $sms = new Sms();
        $result = $sms->send($phone, $signName, $templateCode, $code);

        if ($result['Code'] == 'OK') {
            Session::set($phone, $code);
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

        $verifyCode = Session::get($phone);

        if ($vCode != $verifyCode) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '验证码错误');
            exit;
        }

        Session::destroy();

        $userModel = new UserModel();

        if (!$userModel->checkPhoneExist($phone)) {
            $data = [
                'phone' => $phone,
                'createTime' => currentTime(),
                'updateTime' => currentTime()
            ];

            $insertRow = $userModel->save($data);

            if ($insertRow < 0) {
                util::printResult($GLOBALS['ERROR_REGISTER'], '注册出错');
                exit;
            }
        }

        $detail = $userModel->getByPhone($phone);
        $detailData = $detail->toArray();
        $userId = $detailData['id'];

        $token = password_hash($userId . $phone, PASSWORD_DEFAULT);
        $id_token = $userId . '|' . $token;

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
        $userLoginHistoryModel = new UserLoginHistoryModel();
        $result = $userLoginHistoryModel->save($array);

        if ($result > 0) {
            $arr = [
                'phone' => $phone,
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
            return false;
        }
        $data = explode('|', $id_token);
        $userId = $data['0'];
        $token = $data['1'];
        $userLoginHistoryModel = new UserLoginHistoryModel();
        $count = $userLoginHistoryModel->countByIdToken($userId, $token);
        if ($count > 0) {
//            $GLOBALS['indexUserId'] = $userId;
            $GLOBALS['userId'] = $userId;
            return true;
        }
        return false;
    }
}