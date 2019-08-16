<?php

namespace app\api\controller\v1;

use app\api\model\PositionCateModel;
use think\Request;
use Check;
use Util;

class PositionCate extends IndexBase
{

    /**
     * 获取有数据的分类(需改进)
     * @todo 需要改进
     */
    public function getCateByGroup()
    {
        $cateModel = new PositionCateModel();
        $list = $cateModel->getCateDataGroupById();
        foreach ($list as $k => $v) {
            $detail = $cateModel->getDetail($v['pid']);
            $detailData = $detail->toArray();
            array_push($list,$detailData);
        }
        $list = assoc_unique($list,'id');
        $tree = generateTree($list,'pid');

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $tree);
    }

    /**
     * 获取所有的分类tree
     * @throws \think\exception\DbException
     */
    public function getAllByTree()
    {
        $params = Request::instance()->param();
        $type = Check::checkInteger($params['type'] ?? 0); //默认0

        $cateModel = new PositionCateModel();

        $data = $cateModel->getAll();
        $tree = generateTree($data->toArray(), 'pid');

        if ($type == 1) {
            $tree = generateTree1($data->toArray(), 'pid');
        }

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $tree);
    }


    /**
     * 获取一级分类
     */
    public function getTopCategoryPage()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $positionCateModel = new PositionCateModel();
        $result = $positionCateModel->getTopCate($pageIndex, $pageSize);
        $data['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getAllTopCategory()
    {
        $positionCateModel = new PositionCateModel();
        $result = $positionCateModel->getTopCateWithoutPage();
        $data['list'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取下一级分类
     */
    public function getNextCategory()
    {
        $params = Request::instance()->param();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $positionCateModel = new PositionCateModel();
        $result = $positionCateModel->getNextCate($categoryId, $pageIndex, $pageSize);
        $data['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getAllNextCategory()
    {
        $params = Request::instance()->param();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');

        $positionCateModel = new PositionCateModel();
        $result = $positionCateModel->getNextCateWithoutPage($categoryId);
        $data['list'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    /**
     * 根据分类id获取详情
     */
    public function getDetail()
    {
        $params = Request::instance()->param();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');

        $categoryManagementModel = new PositionCateModel();
        $detail = $categoryManagementModel->getDetail($categoryId);

        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}