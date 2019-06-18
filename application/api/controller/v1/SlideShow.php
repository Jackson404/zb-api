<?php

namespace app\api\controller\v1;

use app\api\model\SlideShowModel;
use think\Request;
use Util\Check;
use Util\Upload;
use Util\Util;

/**
 * 轮播图
 * Class SlideShow
 * @package app\api\controller\v1
 */
class SlideShow extends AdminBase
{

    /**
     * 添加轮播图
     */
    public function add()
    {
        $params = Request::instance()->request();
//        $upload = new Upload();
//        $imgUrl = $upload->uploadImage('imgUrl');
        $imgUrl = Check::check($params['imgUrl'] ?? '');
        $turnUrl = $params['turnUrl'] ?? '';
        $remark = $params['remark'] ?? '';
        $userId = $GLOBALS['userId'];

        if ($imgUrl == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $slideModel = new SlideShowModel();
        $slideModel->imgUrl = $imgUrl;
        $slideModel->turnUrl = $turnUrl;
        $slideModel->remark = $remark;
        $slideModel->createBy = $userId;
        $slideModel->updateBy = $userId;
        $slideModel->createTime = currentTime();
        $slideModel->updateTime = currentTime();

        $slideModel->save();
        $id = $slideModel->getAttr('id');
        if ($id > 0) {
            $data['id'] = $id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
        }
    }

    /**
     * 编辑轮播图
     */
    public function edit()
    {
        $params = Request::instance()->request();
//        $upload = new Upload();
        $slideShowId = Check::checkInteger($params['id'] ?? '');
//        $imgUrl = $upload->uploadImage('imgUrl');
        $imgUrl = Check::check($params['imgUrl'] ?? '');
        $turnUrl = $params['turnUrl'] ?? '';
        $remark = $params['remark'] ?? '';
        $userId = $GLOBALS['userId'];

        if ($imgUrl == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $slideModel = new SlideShowModel();
        $slideModel->id = $slideShowId;
        $slideModel->imgUrl = $imgUrl;
        $slideModel->turnUrl = $turnUrl;
        $slideModel->remark = $remark;
        $slideModel->updateBy = $userId;
        $slideModel->updateTime = currentTime();

        $result = $slideModel->isUpdate(true)->save();

        if ($result > 0) {
            $data['updateRow'] = $result;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '更新失败');
            exit;
        }
    }

    /**
     * 获取所有的轮播图
     * @throws \think\exception\DbException
     */
    public function getAll()
    {
        // 使用闭包查询
        $list = SlideShowModel::all(function ($query) {
            $query->where('isDelete', 0)->order('id', 'desc');
        });
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    /**
     * 删除轮播图
     */
    public function delById()
    {
        $params = Request::instance()->request();
        $slideShowId = $params['id'] ?? '';
        if ($slideShowId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $slideShowModel = new SlideShowModel();
        $delRow = $slideShowModel->save(['isDelete' => 1], function ($query) use ($slideShowId) {
            $query->where('id', $slideShowId);
        });

        if ($delRow > 0) {
            $data['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }
    }

}