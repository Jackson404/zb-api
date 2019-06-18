<?php

namespace app\api\controller\v1;

use app\api\model\LabelManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

/**
 * 标签管理
 * Class LabelManagement
 * @package app\api\controller\v1
 */
class LabelManagement extends AdminBase
{
    /**
     * 添加标签
     */
    public function add()
    {
        $params = Request::instance()->request();
        $name = Check::check($params['name'] ?? '');
        $userId = $GLOBALS['userId'];

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $labelManagementModel = new LabelManagementModel();

        if ($labelManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }

        $data = [
            'name' => $name,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $labelManagementModel->save($data);
        $id = $labelManagementModel->id;

        if ($insertRow > 0) {
            $arr['id'] = $id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }

    }

    /**
     * 编辑标签
     */
    public function edit()
    {
        $params = Request::instance()->request();
        $labelId = Check::checkInteger($params['labelId'] ?? '');
        $name = Check::check($params['name'] ?? '');
        $userId = $GLOBALS['userId'];

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $labelManagementModel = new LabelManagementModel();

        if ($labelManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }
        $data = [
            'name' => $name,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $updateRow = $labelManagementModel->edit($labelId, $data);

        if ($updateRow > 0) {
            $arr['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '编辑失败');
        }
    }

    /**
     * 删除标签
     */
    public function del()
    {
        $params = Request::instance()->request();
        $labelId = Check::checkInteger($params['labelId'] ?? '');

        $labelManagementModel = new LabelManagementModel();
        $data = [
            'id' => $labelId,
            'isDelete' => 1
        ];

        $delRow = $labelManagementModel->isUpdate(true)->save($data);

        if ($delRow > 0) {
            $arr['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
        }
    }

    /**
     * 分页获取标签
     */
    public function getByPage()
    {
        $params = Request::instance()->request();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);
        $labelManagementModel = new LabelManagementModel();
        $page = $labelManagementModel->getByPage($pageIndex, $pageSize);

        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}