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

    /*
     * 上传文件
     * */
    public function uploadFiles($name)
    {
        $file = Request::instance()->file($name);
        $path = ROOT_PATH . 'public' . DS . 'uploads';
        $info = $file->validate(['ext' => 'apk'])->move($path);
        if ($info) {
            $name = $info->getSaveName();
            $size = $info->getSize();
            $url = $GLOBALS['CDN_URL'] . '/public/uploads/' . $name;
            $data = [
                'size' => $size,
                'apk_url' => $url
            ];
            return $data;
        } else {
            return false;
        }
    }

    /*
     * 上传文件txt
     * */
    public function uploadLogInMobile($name)
    {
        $file = Request::instance()->file($name);
        if ($file == null) {
            return false;
        }
        $path = ROOT_PATH . 'public' . DS . 'uploads';

        $info = $file
            ->validate(['ext' => 'txt'])
            ->move($path);

        if ($info) {
            $name = $info->getSaveName();
            $url = $GLOBALS['CDN_URL'] . '/public/uploads/' . $name;

            $arr = [
                'url' => $url,
                'create_time' => date('Y-m-d H:i:s', time()),
                'update_time' => date('Y-m-d H:i:s', time())
            ];
            $count = Db::name('log_phone')->insert($arr);
            if ($count > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
    * 上传文件
    * */
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
//            $ip = $_SERVER['REMOTE_ADDR'];
//            $url = $GLOBALS['CDN_URL'] . '/public/uploads/' . $name;
            $url = '/public/uploads/' . $name;
            return $url;
        } else {
            return false;
        }
    }

    /*
     * 多图片上传
     * @image
     * */
    public function uploadImages($size, $ext)
    {
        // 获取表单上传文件
        $files = request()->file('image');
        $images = array();
        foreach ($files as $file) {
            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($size != '' && $ext != '') {
                $info = $file->validate(['size' => $size * 1024 * 1024, 'ext' => $ext])->move(ROOT_PATH . 'public' . DS . 'images');
            }
            if ($size == '' && $ext != '') {
                $info = $file->validate(['ext' => $ext])->move(ROOT_PATH . 'public' . DS . 'images');
            }
            if ($ext == '' && $size != '') {
                $info = $file->validate(['size' => $size * 1024 * 1024])->move(ROOT_PATH . 'public' . DS . 'images');
            }
            if ($ext == '' && $size == '') {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'images');
            }

            if ($info) {
                $name = $info->getSaveName();
                $url = $GLOBALS['CDN_URL'] . '/public/images/' . $name;
                array_push($images, $url);
            } else {
                // 上传失败获取错误信息
                Util::printResult($GLOBALS['ERROR_FILE_UPLOAD'], $file->getError());
                exit;

            }
        }
        return $images;
    }

    /*
   * 多图片上传
   * @image
   * */
    /**
     * @param $size
     * @param $ext
     * @return array
     */
    public function uploadFilesByPhone($size, $ext)
    {
        // 获取表单上传文件
        $files = request()->file('file');
        $images = array();
        foreach ($files as $file) {
            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($size != '' && $ext != '') {
                $info = $file->validate(['size' => $size * 1024 * 1024, 'ext' => $ext])->move(ROOT_PATH . 'public' . DS . 'uploads');
            }
            if ($size == '' && $ext != '') {
                $info = $file->validate(['ext' => $ext])->move(ROOT_PATH . 'public' . DS . 'uploads');
            }
            if ($ext == '' && $size != '') {
                $info = $file->validate(['size' => $size * 1024 * 1024])->move(ROOT_PATH . 'public' . DS . 'uploads');
            }
            if ($ext == '' && $size == '') {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            }

            if ($info) {
                $name = $info->getSaveName();
                $url = $GLOBALS['CDN_URL'] . '/public/uploads/' . $name;
                array_push($images, $url);
            } else {
                // 上传失败获取错误信息
                Util::printResult($GLOBALS['ERROR_FILE_UPLOAD'], $file->getError());
                exit;

            }
        }
        return $images;
    }


}