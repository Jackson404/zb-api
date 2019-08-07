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
        $tmpInfo = $file->getInfo();
        $object = $tmpInfo['name'];
        $filepath = $tmpInfo['tmp_name'];

        try {
            $ossClient = new OssClient($GLOBALS['ACCESS_KEY_ID'], $GLOBALS['ACCESS_KEY_SECRET'], $GLOBALS['ENDPOINT']);
            $res = $ossClient->uploadFile($GLOBALS['BUCKET'], $object, $filepath);
            $data['imgUrl'] = 'https://' . $GLOBALS['PIC_SERVER'] . '/' . $object;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } catch (OssException $e) {
            Util::printResult($e->getCode(), $e->getMessage());
            exit;
        }
    }
}