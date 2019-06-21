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

        if ($grantType != 'zhengbu_client_credential') {
            Util::printResult('-1', 'grant_type is not right');
            exit;
        }
        if ($webId != $GLOBALS['WEB_ID']) {
            Util::printResult('-2', 'web_id is not right');
            exit;
        }
        if ($secret != $GLOBALS['WEB_SECRET']) {
            Util::printResult('-3', 'web_secret is not right');
            exit;
        }

        $randomCode = Util::generateRandomCode(8);

        $accessToken = $this->authRule($grantType, $webId, $secret, $randomCode);

        $expiresIn = 7200;

        $redis = new Redis();

        $redis->set('accessTokenApi', $accessToken, $expiresIn);

        $data['access_token'] = $accessToken;
        $data['expires_in'] = $expiresIn;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }


    public function respond()
    {
        //验证身份
        $params = Request::instance()->request();
        $accessToken = $params['accessToken'] ?? '';

        $redis = new Redis();

        if ($accessToken == ''){
            Util::printResult('-001','缺少access_token');
            exit;
        }

        $result = $redis->get('accessTokenApi');

        if (!$result){
            Util::printResult('-002','access_token过期，请重新请求');
            exit;
        }

        if ($accessToken != $result){
            Util::printResult('-003','access_token错误');
            exit;
        }
    }

    private function authRule($grantType, $webId, $secret, $randomCode)
    {
        $arr = [
            'grantType' => $grantType,
            'webId' => $webId,
            'webSecret' => $secret,
            'randomCode' => $randomCode
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