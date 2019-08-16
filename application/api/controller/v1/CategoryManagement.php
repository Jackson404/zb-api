<?php

namespace app\api\controller\v1;

use app\api\model\CategoryManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class CategoryManagement extends IndexBase
{
    /**
     * 获取所有的分类tree
     * @throws \think\exception\DbException
     */
    public function getAllByTree()
    {
        $params = Request::instance()->param();
        $type = Check::checkInteger($params['type'] ?? 0); //默认0

        $cateModel = new CategoryManagementModel();

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

        $categoryManagementModel = new CategoryManagementModel();
        $result = $categoryManagementModel->getTopCate($pageIndex, $pageSize);
        $data['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getAllTopCategory()
    {
        $categoryManagementModel = new CategoryManagementModel();
        $result = $categoryManagementModel->getTopCateWithoutPage();
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

        $categoryManagementModel = new CategoryManagementModel();
        $result = $categoryManagementModel->getNextCate($categoryId, $pageIndex, $pageSize);
        $data['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getAllNextCategory()
    {
        $params = Request::instance()->param();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');

        $categoryManagementModel = new CategoryManagementModel();
        $result = $categoryManagementModel->getNextCateWithoutPage($categoryId);
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

        $categoryManagementModel = new CategoryManagementModel();
        $detail = $categoryManagementModel->getDetail($categoryId);

        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}