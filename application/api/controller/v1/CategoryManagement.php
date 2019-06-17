<?php

namespace app\api\controller\v1;

use app\api\model\CategoryManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class CategoryManagement extends AdminBase
{
    /**
     * 添加分类
     */
    public function add()
    {
        $params = Request::instance()->request();
        $name = $params['name'] ?? '';
        $pid = Check::checkInteger($params['pid'] ?? 0);

        $userId = $GLOBALS['userId'];

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $categoryManagementModel = model('CategoryManagementModel');

        if ($categoryManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字已存在');
            exit;
        }

        $categoryManagementModel->data(
            [
                'name' => $name,
                'pid' => $pid,
                'createTime' => currentTime(),
                'createBy' => $userId,
                'updateTime' => currentTime(),
                'updateBy' => $userId
            ]
        );
        $insertRow = $categoryManagementModel->save();

        if ($insertRow > 0) {
            $id = $categoryManagementModel->id;
            $data['id'] = $id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }
    }

    /**
     * 编辑分类
     */
    public function edit()
    {
        $params = Request::instance()->request();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $name = Check::check($params['name'] ?? '');
        $pid = Check::checkInteger($params['pid'] ?? '');

        $userId = $GLOBALS['userId'];

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $categoryManagementModel = new CategoryManagementModel();

        if ($categoryManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字已存在');
            exit;
        }

        $data = [
            'name' => $name,
            'pid' => $pid,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];
        $updateRow = $categoryManagementModel->save($data, function ($query) use ($categoryId) {
            $query->where('id', $categoryId);
        });

        $arr['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    /**
     * 获取所有的分类tree
     * @throws \think\exception\DbException
     */
    public function getAllByTree()
    {

        $data = CategoryManagementModel::all(['isDelete' => 0]);
        $tree = generateTree($data->toArray(), 'pid');
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

        $categoryManagementModel = new CategoryManagementModel();
        $result = $categoryManagementModel->getTopCate($pageIndex, $pageSize);
        $data['page'] = $result;
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

        $categoryManagementModel = new CategoryManagementModel();
        $result = $categoryManagementModel->getNextCate($categoryId, $pageIndex, $pageSize);
        $data['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 删除分类 同时会删除下面的子分类
     * @throws \think\exception\DbException
     */
    public function del()
    {
        $params = Request::instance()->request();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');

        $result = CategoryManagementModel::all(['isDelete' => 0]);
        $data = $result->toArray();
        $childIdArr = getChildId($data, $categoryId);
        array_push($childIdArr, $categoryId);
        $childIds = implode(',', $childIdArr);

        $categoryManagementModel = new CategoryManagementModel();
        $result = $categoryManagementModel->del($childIds);
        $arr['delRows'] = $result;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }


}