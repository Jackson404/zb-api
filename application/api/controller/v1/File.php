<?php

namespace app\api\controller\v1;

use OSS\Core\OssException;
use OSS\OssClient;
use think\Request;
use Util\Upload;
use Util\Util;

class File extends AuthBase
{
    /**
     * 单文件上传
     */
    public function upload()
    {
        $upload = new Upload();
        $url = $upload->uploadImage('img');
        $data['imgUrl'] = $url;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 阿里云oss上传
     */
    public function oss()
    {
        $file = Request::instance()->file('img');

        if ($file == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '参数错误');
            exit;
        }

        $tmpInfo = $file->getInfo();
        //$type = $tmpInfo['type'];
        //$typeArr = explode('/',$type);
        $object = $tmpInfo['name'];

//        $nowDate = date('Ymd',time());
//        $to_object = Util::generateRandomCode(22).'.'.$typeArr[1];
        $filepath = $tmpInfo['tmp_name'];

        try {
            $ossClient = new OssClient($GLOBALS['ACCESS_KEY_ID'], $GLOBALS['ACCESS_KEY_SECRET'], $GLOBALS['ENDPOINT']);

//            // 创建文件夹
//            $ossClient->createObjectDir($GLOBALS['BUCKET'],$nowDate);
//
//            $exist = $ossClient->doesObjectExist($GLOBALS['BUCKET'], $object);
//            if ($exist){
//                $ossClient->copyObject($GLOBALS['BUCKET'], $object, $GLOBALS['BUCKET'], $to_object);
//                echo 'exist';
//            }
//            exit;

            $res = $ossClient->uploadFile($GLOBALS['BUCKET'], $object, $filepath);

            $data['imgUrl'] = 'https://' . $GLOBALS['PIC_SERVER'] . '/' . $object;
//            var_dump($object);
//            var_dump($res);
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } catch (OssException $e) {
            Util::printResult($e->getCode(), $e->getMessage());
            exit;
        }
    }
}