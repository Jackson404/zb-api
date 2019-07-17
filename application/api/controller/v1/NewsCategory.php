<?php

namespace app\api\controller\v1;

use app\api\model\NewsCategoryModel;
use think\Request;
use Util\Check;
use Util\Util;

class NewsCategory extends IndexBase
{
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