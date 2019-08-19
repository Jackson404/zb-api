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
class LabelManagement extends IndexBase
{
    /**
     * 分页获取标签
     */
    public function getByPage()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);
        $labelManagementModel = new LabelManagementModel();
        $page = $labelManagementModel->getByPage($pageIndex, $pageSize);

        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取所有的标签
     */
    public function getAllLabels()
    {
        $labelManagementModel = new LabelManagementModel();
        $page = $labelManagementModel->getAllLabels();
        $data['list'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


    /**
     * 获取标签详情
     */
    public function getDetail()
    {
        $params = Request::instance()->param();
        $labelId = Check::checkInteger($params['labelId'] ?? '');
        $labelManagementModel = new LabelManagementModel();
        $detail = $labelManagementModel->getDetail($labelId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}