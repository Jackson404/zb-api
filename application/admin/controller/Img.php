<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29 0029
 * Time: 17:10
 */

namespace app\admin\controller;


use app\admin\common\Upload;
use app\admin\common\Util;
use think\Controller;
use think\Db;
use think\Request;

class Img extends Controller
{


    /**
     * 上传图片 单张
     */
    public function upload()
    {
        $data = Request::instance()->param();
        $turnUrl = $data['turn_url'];
        $remark = $data['remark'];
        $upload = new Upload();
        $name = 'image';
        $imgUrl = $upload->uploadImage($name);
        $data = [
            'img_url' => $imgUrl,
            'turn_url' => $turnUrl,
            'remark' => $remark,
            'create_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time())
        ];
        $count = Db::name('upload_img')->insert($data);
        $data['uploadCount'] = $count;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }

    /**
     * 获取图片列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getImgList()
    {
        $result = Db::name('upload_img')->order('id', 'DESC')->select();
        $data['imgList'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
        exit;
    }
}