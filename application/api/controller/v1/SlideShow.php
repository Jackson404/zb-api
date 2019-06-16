<?php

namespace app\api\controller\v1;

use app\api\model\SlideShowMModel;
use think\Controller;
use think\Request;
use Util\Upload;
use Util\Util;

class SlideShow extends Controller
{

    public function add()
    {
        $params = Request::instance()->request();
        $upload = new Upload();
        $imgUrl = $upload->uploadImage('imgUrl');
//        $imgUrl = $params['imgUrl'] ?? '';
        $turnUrl = $params['turnUrl'] ?? '';
        $remark = $params['remark'] ?? '';

        $slideModel = new SlideShowMModel();
        $slideModel->imgUrl = $imgUrl;
        $slideModel->turnUrl = $turnUrl;
        $slideModel->remark = $remark;
        $slideModel->createBy = 1;
        $slideModel->updateBy = 1;
        $slideModel->createTime = currentTime();
        $slideModel->updateTime = currentTime();

        $slideModel->save();
        $id = $slideModel->getAttr('id');
        Util::printResult($GLOBALS['ERROR_SUCCESS'],$id);

    }
}