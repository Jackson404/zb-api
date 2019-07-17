<?php

namespace app\api\controller\v1;

use app\api\model\PositionManagementModel;
use app\api\model\ResumeModel;
use app\api\model\UserApplyPositionModel;
use app\api\model\UserLoginHistoryModel;
use app\api\model\UserModel;
use app\api\model\UserPositionIntensionModel;
use Curl\Curl;
use Sms;
use think\cache\driver\Redis;
use think\Request;
use Util\Check;
use Util\Util;

class User extends IndexBase
{
    public function sendSms()
    {
        $params = Request::instance()->request();
        $phone = Check::check($params['phone'] ?? '', 11, 11);

        $signName = '正步网络科技';
        $templateCode = 'SMS_164030786';
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

        $redis = new Redis();
        $verifyCode = $redis->get($phone);

        if ($vCode != $verifyCode) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '验证码错误');
            exit;
        }

        $userModel = new UserModel();

        if (!$userModel->checkPhoneExist($phone)) {
            $username = '正步_' . Util::generateRandomCode(6);
            $avatar = '/public/avatar/a1.png';
            $data = [
                'avatar' => $avatar,
                'name' => $username,
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
        $username = $detailData['name'];
        $avatar = $detailData['avatar'];

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
                'uid' => $userId,
                'avatar' => $avatar,
                'name' => $username,
                'phone' => $phone,
                'id_token' => $id_token
            ];
            $resumeModel = new ResumeModel();
            if ($resumeModel->checkUserHasCreateResume($userId)) {
                $arr['hasResume'] = true;

                $userApplyPositionModel = new UserApplyPositionModel();
                $list = $userApplyPositionModel->getUserApplyList($userId);
                $listData = $list->toArray();

                $arr['list'] = [
                    'applyPositionTotal' => count($listData),
                    'see' => 100
                ];
            } else {
                $arr['hasResume'] = false;
                $arr['list'] = [];
            }

            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '登录失败');
            exit;
        }
    }

    public function changePhone()
    {
        $params = Request::instance()->request();
        $phone = Check::check($params['phone'] ?? '', 11, 11);
        $vCode = Check::check($params['vCode'] ?? '');
        $userId = $GLOBALS['userId'];

        $userModel = new UserModel();

        if ($userModel->checkPhoneExist($phone)) {
            Util::printResult($GLOBALS['ERROR_REGISTER_DUPLICATEPHONE'], '手机号重复');
            exit;
        }

        $redis = new Redis();
        $verifyCode = $redis->get($phone);


        if ($vCode != $verifyCode) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '验证码错误');
            exit;
        }

        $data = [
            'id' => $userId,
            'phone' => $phone,
            'updateTime' => currentTime()
        ];

        $updateRow = $userModel->isUpdate(true)->save($data);

        if ($updateRow < 0) {
            util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '更新出错');
            exit;
        }


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
                'uid' => $userId,
                'phone' => $phone,
                'id_token' => $id_token
            ];
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_LOGIN'], '修改之后,登录失败');
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
            $GLOBALS['userId'] = $userId;
            return true;
        }
        return false;
    }

    public function userCenter()
    {
        $userId = $GLOBALS['userId'];
        $resumeModel = new ResumeModel();
        $resume = $resumeModel->getUserResume($userId);
        $userPositionIntension = new UserPositionIntensionModel();
        $positionIntension = $userPositionIntension->getPositionIntensionByUserId($userId);

        $userApplyPositionModel = new UserApplyPositionModel();
        $list = $userApplyPositionModel->getUserApplyList($userId);
        $listData = $list->toArray();
        $positionModel = new PositionManagementModel();
        foreach ($listData as $k => $v) {
            $positionId = $v['positionId'];
            $positionDetail = $positionModel->getDetailForApply($positionId);
            $listData[$k]['positionDetail'] = $positionDetail;
        }

        $data['total'] = count($listData);
        $data['list'] = $listData;
        $data['resume'] = $resume;
        $data['positionIntension'] = $positionIntension;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    public function getUserInfo()
    {
        $userId = $GLOBALS['userId'];
        $userModel = new UserModel();
        $userInfo = $userModel->getUserInfoByUserId($userId);
        $arr = [
            'uid' => $userId,
            'avatar' => $userInfo['avatar'],
            'name' => $userInfo['name'],
            'phone' => $userInfo['phone']
        ];
        $resumeModel = new ResumeModel();
        if ($resumeModel->checkUserHasCreateResume($userId)) {
            $arr['hasResume'] = true;

            $userApplyPositionModel = new UserApplyPositionModel();
            $list = $userApplyPositionModel->getUserApplyList($userId);
            $listData = $list->toArray();

            $arr['list'] = [
                'applyPositionTotal' => count($listData),
                'see' => 100
            ];
        } else {
            $arr['hasResume'] = false;
            $arr['list'] = [];
        }

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);

    }

    // 小程序默认登录
    public function codeToSession()
    {
        $params = Request::instance()->request();
        $code = $params['code'] ?? '';
        if ($code == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $url = $GLOBALS['mini_url'] . '?appid=' . $GLOBALS['mini_appid'] . '&secret=' . $GLOBALS['mini_secret'] .
            '&js_code=' . $code . '&grant_type=authorization_code';

        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($url);

        if ($curl->error) {
            Util::printResult($curl->errorCode, $curl->errorMessage);
            exit;
        }
        $response = $curl->response;

        $res = json_decode($response, true);

        if (array_key_exists('errcode',$res)){
            if ($res['errcode'] != 0){
                Util::printResult($res['errcode'],$res['errmsg']);
                exit;
            }
        }

        $openid = $res['openid'];

        $userModel = new UserModel();
        if ($userModel->checkMiniOpenIdExist($openid)) {
            $detail = $userModel->getByMiniOpenId($openid);
            $detailData = $detail->toArray();
            $arr = [
                'u_id' => $detailData['id'],
                'phone' => $detailData['phone'],
                'avatar' => $detailData['avatar']
            ];
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        }
        $arr = array(
            'phone' => 0,
            'openid' => $openid
        );
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
        exit;
    }

}
