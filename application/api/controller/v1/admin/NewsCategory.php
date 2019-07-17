<?php

namespace app\api\controller\v1\admin;

use app\api\model\NewsCategoryModel;
use think\Request;
use Util\Check;
use Util\Util;

class NewsCategory extends AdminBase
{
    public function add()
    {
        $params = Request::instance()->request();
        $name = Check::check($params['name'] ?? '');
        $userId = $GLOBALS['userId'];

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $newsCategoryModel = new NewsCategoryModel();

        if ($newsCategoryModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }

        $arr = [
            'name' => $name,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $newsCategoryModel->save($arr);
        if ($insertRow > 0) {
            $data['id'] = $newsCategoryModel->id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }
    }

    public function edit()
    {
        $params = Request::instance()->request();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $name = Check::check($params['name'] ?? '');
        $userId = $GLOBALS['userId'];

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $newsCategoryModel = new NewsCategoryModel();

        $detail = $newsCategoryModel->getDetail($categoryId);
        if ($detail['name'] != $name && $newsCategoryModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }

        $arr = [
            'name' => $name,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $updateRow = $newsCategoryModel->edit($categoryId, $arr);
        if ($updateRow > 0) {
            $data['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '编辑失败');
            exit;
        }
    }

    public function del()
    {
        $params = Request::instance()->request();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');

        $newsCategoryModel = new NewsCategoryModel();

        $arr = [
            'id' => $categoryId,
            'isDelete' => 1
        ];
        $delRow = $newsCategoryModel->isUpdate(true)->save($arr);

        if ($delRow > 0) {
            $data['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }
    }

    public function getAll()
    {
        $newsCategoryModel = new NewsCategoryModel();
        $list = $newsCategoryModel::all(['isDelete' => 0]);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getDetail()
    {
        $params = Request::instance()->request();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');

        $newsCategoryModel = new NewsCategoryModel();
        $detail = $newsCategoryModel->getDetail($categoryId);

        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}