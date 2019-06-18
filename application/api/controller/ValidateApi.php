<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use Util\Util;

class ValidateApi extends Controller
{

    public function get()
    {
        $timeStamp = time();
        $randomStr = Util::generateRandomCode(8);
        $signature = $this->arithmetic($timeStamp, $randomStr);
        $data = [
            'timeStamp' => $timeStamp,
            'randomStr' => $randomStr,
            'signature' => $signature
        ];
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    public function respond()
    {
        //验证身份
        $params = Request::instance()->request();
        $timeStamp = $params['t'] ?? '';
        $randomStr = $params['r'] ?? '';
        $signature = $params['s'] ?? '';

        $str = $this->arithmetic($timeStamp, $randomStr);
        if ($str != $signature) {
            Util::printResult($GLOBALS['REQUEST_ILLEGAL'], '非法请求');
            exit;
        }
    }

    /**
     * @param $timeStamp 时间戳
     * @param $randomStr 随机字符串
     * @return string 返回签名
     */
    public function arithmetic($timeStamp, $randomStr)
    {
        $arr['timeStamp'] = $timeStamp;
        $arr['randomStr'] = $randomStr;
        $arr['token'] = $GLOBALS['TOKEN_API'];
        //按照首字母大小写顺序排序
        sort($arr, SORT_STRING);
        //拼接成字符串
        $str = implode($arr);
        //进行加密
        $signature = sha1($str);
        $signature = md5($signature);
        //转换成大写
        $signature = strtoupper($signature);
        return $signature;
    }

}