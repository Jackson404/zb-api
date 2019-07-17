<?php

namespace app\api\controller\v1\admin;

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
        $imgUrl = Check::check($params['imgUrl'] ?? '');
        $imgUrl = stripslashes($imgUrl);
        $turnUrl = $params['turnUrl'] ?? '';
        $remark = $params['remark'] ?? '';
        $type = Check::checkInteger($params['type'] ?? 1); //1 轮播图 2广告位
        $sort = Check::checkInteger($params['sort'] ?? 0); // 值越大越靠前
        $userId = $GLOBALS['userId'];

        if ($imgUrl == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $slideModel = new SlideShowModel();
        $slideModel->imgUrl = $imgUrl;
        $slideModel->turnUrl = $turnUrl;
        $slideModel->remark = $remark;
        $slideModel->type = $type;
        $slideModel->sort = $sort;
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
        $slideShowId = Check::checkInteger($params['id'] ?? '');
        $imgUrl = Check::check($params['imgUrl'] ?? '');
        $imgUrl = stripslashes($imgUrl);
        $turnUrl = $params['turnUrl'] ?? '';
        $remark = $params['remark'] ?? '';
        $type = Check::checkInteger($params['type'] ?? 1); //1 轮播图 2广告位
        $sort = Check::checkInteger($params['sort'] ?? 0); // 值越大越靠前
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
        $slideModel->type = $type;
        $slideModel->sort = $sort;
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
     * 获取所有的轮播图  1 轮播图（默认） 2 广告图  0 全部
     * @throws \think\exception\DbException
     */
    public function getAll()
    {
        $params = Request::instance()->request();
        $type = Check::checkInteger($params['type'] ?? 1);

        // 使用闭包查询
        $list = SlideShowModel::all(function ($query) use ($type) {
            if ($type == 0) {
//                $query->where('isDelete', '=', 0)->order('sort', 'desc');
                $query->where('isDelete', '=', 0)->order('id', 'desc');
            } else {
//                $query->where('isDelete', '=', 0)->where('type', '=', $type)->order('sort', 'desc');
                $query->where('isDelete', '=', 0)->where('type', '=', $type)->order('id', 'desc');
            }
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

    /**
     * 获取轮播图详情
     */
    public function getDetail()
    {
        $params = Request::instance()->request();
        $slideShowId = $params['id'] ?? '';
        if ($slideShowId == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $slideShowModel = new SlideShowModel();
        $detail = $slideShowModel->getDetail($slideShowId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}