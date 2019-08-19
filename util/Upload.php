<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31 0031
 * Time: 10:14
 */

namespace Util;

use think\Db;
use think\exception\ValidateException;
use think\Request;

class Upload
{
    /**
     * 上传文件
     * @param $name
     * @return bool|string
     */
    public function uploadImage($name)
    {
        $file = Request::instance()->file($name);
        if (empty($file)){
            Util::printResult($GLOBALS['ERROR_FILE_UPLOAD'],'文件上传错误');
            exit;
        }
        $path = ROOT_PATH . 'public' . DS . 'uploads';
        $info = $file->validate(['ext' => 'jpg,gif,png'])->move($path);
        if ($info) {
            $name = $info->getSaveName();
            $url = '/uploads/' . $name;
            return $url;
        } else {
            return false;
        }
    }

}