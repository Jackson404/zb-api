<?php

namespace app\api\controller;

use think\cache\driver\Redis;
use think\Controller;
use think\Request;
use Util\Util;

class Auth extends Controller
{
    public function getToken()
    {
        $params = Request::instance()->request();
        $grantType = $params['grantType'] ?? '';
        $webId = $params['webId'] ?? '';
        $secret = $params['webSecret'] ?? '';

        if ($grantType != md5('zhengbu_client_credential')) {
            Util::printResult('-1', 'grant_type is not right');
            exit;
        }
        if ($webId != md5($GLOBALS['WEB_ID'])) {
            Util::printResult('-2', 'web_id is not right');
            exit;
        }
        if ($secret != md5($GLOBALS['WEB_SECRET'])) {
            Util::printResult('-3', 'web_secret is not right');
            exit;
        }

        $timeStamp = time();
        //$randomCode = Util::generateRandomCode(8);

        //$accessToken = $this->authRule($grantType, $webId, $secret, $randomCode);
        $accessToken = $this->authRule($grantType, $webId, $secret, $timeStamp);

        // $expiresIn = 7200;

        $redis = new Redis();

        $redis->set('accessTokenApi_' . $timeStamp, $accessToken);

        $data['access_token'] = $timeStamp . '|' . $accessToken;
        // $data['expires_in'] = $expiresIn;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }


    public function respond()
    {
        //验证身份
        $params = Request::instance()->request();
        $accessToken = $params['accessToken'] ?? '';


        if ($accessToken == '') {
            Util::printResult('-001', '缺少access_token');
            exit;
        }

        $arr = explode('|', $accessToken);
        $timeStamp = $arr[0];
        $accessTokenResult = $arr[1];

        $redis = new Redis();

        $result = $redis->get('accessTokenApi_' . $timeStamp);

//        if (!$result) {
//            Util::printResult('-002', 'access_token过期，请重新请求');
//            exit;
//        }

        if ($accessTokenResult != $result) {
            Util::printResult('-003', 'access_token错误');
            exit;
        }
    }

    private function authRule($grantType, $webId, $secret, $timeStamp)
    {
        $arr = [
            'grantType' => $grantType,
            'webId' => $webId,
            'webSecret' => $secret,
//            'randomCode' => $randomCode
            'timeStamp' => $timeStamp
        ];
        //按照首字母大小写顺序排序
        sort($arr, SORT_STRING);
        //拼接成字符串
        $str = implode($arr);
        //进行加密
        $accessToken = sha1($str);
        $accessToken = md5($accessToken);
        //转换成大写
        $accessToken = strtoupper($accessToken);
        return $accessToken;
    }
}
