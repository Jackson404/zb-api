<?php

namespace app\api\controller\v1;

use app\api\model\PositionCateModel;
use think\Request;
use Util\Check;
use Util\Util;

class PositionCate extends IndexBase
{

    /**
     * 获取所有的分类tree
     * @throws \think\exception\DbException
     */
    public function getAllByTree()
    {
        $params = Request::instance()->request();
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
        $params = Request::instance()->request();
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
        $params = Request::instance()->request();
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
        $params = Request::instance()->request();
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
        $params = Request::instance()->request();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');

        $categoryManagementModel = new PositionCateModel();
        $detail = $categoryManagementModel->getDetail($categoryId);

        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}