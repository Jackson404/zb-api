<?php

namespace app\api\controller\v1\admin;

use Util\Upload;
use Util\Util;

class File extends AdminBase
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

}