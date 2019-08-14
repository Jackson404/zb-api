<?php

namespace app\api\controller\v1;

use app\api\model\IndustryModel;
use think\Request;
use Util\Check;
use Util\Util;

class Industry extends AuthBase
{
    /**
     * 获取所有的分类tree
     * @throws \think\exception\DbException
     */
    public function getAllByTree()
    {
        $params = Request::instance()->param();
        $type = Check::checkInteger($params['type'] ?? 0); //默认0

        $industryModel = new IndustryModel();

        $data = $industryModel->getAll();
        $tree = generateTreeCode($data->toArray(), 'pid');

        if ($type == 1) {
            $tree = generateTreeCode1($data->toArray(), 'pid');
        }

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $tree);
    }


    /**
     * 分页获取一级分类
     */
    public function getTopIndustryPage()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $industryModel = new IndustryModel();
        $result = $industryModel->getTopIndustryPage($pageIndex, $pageSize);
        $data['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取所有的一级分类
     */
    public function getAllTopIndustry()
    {
        $industryModel = new IndustryModel();
        $result = $industryModel->getAllTopIndustry();
        $data['list'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 分页获取下一级分类
     */
    public function getNextIndustryPage()
    {
        $params = Request::instance()->param();
        $code = Check::checkInteger($params['code'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $industryModel = new IndustryModel();
        $result = $industryModel->getNextIndustryPage($code, $pageIndex, $pageSize);
        $data['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取所有的下一级分类
     */
    public function getAllNextIndustry()
    {
        $params = Request::instance()->param();
        $code = Check::checkInteger($params['code'] ?? '');
        $industryModel = new IndustryModel();
        $result = $industryModel->getAllNextIndustry($code);
        $data['list'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 根据分类id获取详情
     */
    public function getDetail()
    {
        $params = Request::instance()->param();
        $industryId = Check::checkInteger($params['industryId'] ?? '');
        $industryModel = new IndustryModel();
        $detail = $industryModel->getDetail($industryId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function filterIndustryInfo()
    {
        $params = Request::instance()->param();
        $info = Check::check($params['info'] ?? '');
        $industryModel = new IndustryModel();
        $r = $industryModel->allIndustryInfo();
        $r = array_column($r->toArray(), 'name', 'code');

        if ($info == '') {
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $r);
            exit;
        } else {
            $input = preg_quote($info, '~'); // don't forget to quote input string!
            $result = preg_grep('~' . $input . '~', $r);
            $result = array_values($result);
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $result);
        }

    }
}