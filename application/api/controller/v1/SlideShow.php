<?php

namespace app\api\controller\v1;

use app\api\model\SlideShowModel;
use think\Request;
use Util\Check;
use Util\Util;

/**
 * 轮播图
 * Class SlideShow
 * @package app\api\controller\v1
 */
class SlideShow extends IndexBase
{
    /**
     * 获取所有的轮播图  1 轮播图（默认） 2 广告图  0 全部
     * @throws \think\exception\DbException
     */
    public function getAll()
    {
        $params = Request::instance()->param();
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

    public function getDetail()
    {
        $params = Request::instance()->param();
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