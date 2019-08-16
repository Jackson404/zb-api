<?php

namespace app\api\controller\v1;

use app\api\model\CompanyManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class CompanyManagement extends IndexBase
{
    public function getDetail()
    {
        $params = Request::instance()->param();
        $companyId = Check::checkInteger($params['companyId'] ?? '');

        $companyManagementModel = new CompanyManagementModel();
        $detail = $companyManagementModel->getDetail($companyId);

        if ($detail != null) {
            $detail['dataBank'] = json_decode($detail['dataBank'], true);
        }

        $arr['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    public function getByPage()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $companyManagementModel = new CompanyManagementModel();
        $page = $companyManagementModel->getByPage($pageIndex, $pageSize);

        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['dataBank'] = json_decode($v['dataBank'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getAll()
    {
        $companyManagementModel = new CompanyManagementModel();
        $result = $companyManagementModel->getAll();

        foreach ($result as $k => $v) {
            $result[$k]['dataBank'] = json_decode($v['dataBank'], true);
        }

        $data['list'] = $result;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function filterCompanyPage()
    {
        $params = Request::instance()->param();
        $areaInfo = Check::check($params['areaInfo'] ?? '');
        $industryInfo = Check::check($params['industryInfo'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $companyModel = new CompanyManagementModel();
        list($r1,$r2) = $companyModel->filterCompanyPage($areaInfo,$industryInfo, $pageIndex, $pageSize);

        foreach ($r1 as $k => $v) {
            $r1[$k]['dataBank'] = json_decode($v['dataBank'], true);
        }

        $data['total'] = $r2;
        $data['pageIndex'] = $pageIndex;
        $data['pageSize'] = $pageSize;
        $data['page'] = $r1;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function filterCompanyByIndustryInfoPage()
    {
        $params = Request::instance()->param();
        $info = Check::check($params['info'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $companyModel = new CompanyManagementModel();

        $page = $companyModel->filterCompanyByIndustryPage($info, $pageIndex, $pageSize);

        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['dataBank'] = json_decode($v['dataBank'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}