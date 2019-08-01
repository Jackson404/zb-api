<?php

namespace app\api\controller\v1;

use app\api\model\PositionManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class PositionManagement extends IndexBase
{

    public function getDetail()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id
        $positionManagementModel = new PositionManagementModel();
        $detail = $positionManagementModel->getDetail($positionId);
        $randomList = $positionManagementModel->getRandomPositionListLimit($positionId,5);
        $detail['labelIds'] = json_decode($detail['labelIds'], true);
        $data['detail'] = $detail;
        $data['randomList'] = $randomList;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getByPage()
    {
        $params = Request::instance()->request();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $positionManagementModel = new PositionManagementModel();

        $page = $positionManagementModel->getByPage($pageIndex, $pageSize);

        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['createDate'] = date('Y-m-d',strtotime($v['createTime']));
            $pageArr[$k]['updateDate'] = date('Y-m-d',strtotime($v['updateTime']));
            $pageArr[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function search()
    {
        $params = Request::instance()->request();
        $searchValue = Check::check($params['searchValue'] ?? '');

        $positionModel = New PositionManagementModel();
        $list = $positionModel->search($searchValue);
        foreach ($list as $k => $v) {
            $list[$k]['createDate'] = date('Y-m-d',strtotime($v['createTime']));
            $list[$k]['updateDate'] = date('Y-m-d',strtotime($v['updateTime']));
            $list[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }

    /**
     * 筛选职位
     */
    public function filter()
    {
        $params = Request::instance()->request();

        $positionCateId = Check::checkInteger($params['positionCateId'] ?? 0); // 职位分类id
        $salary = Check::check($params['salary'] ?? ''); // 薪资
        $labelIds = Check::check($params['labelIds'] ?? ''); //福利待遇  label 字符串
        $education = Check::check($params['education'] ?? ''); //学历
        $workYear = Check::check($params['workYear'] ?? ''); //工作年限
        $isSoldierPriority = Check::checkInteger($params['isSoldierPriority'] ?? 0);//是否军人优先 1,2
        $province = Check::check($params['province'] ?? ''); //省份
        $city = Check::check($params['city'] ?? ''); //市
        $area = Check::check($params['area'] ?? ''); //区
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        if ($positionCateId != 0) {
            $positionSql = "  and p.positionCateId= $positionCateId ";
        } else {
            $positionSql = '';
        }

        if ($salary == '3000元以下') {
            $minPay = 0;
            $maxPay = 3000;
            $salarySql = "  and (p.minPay >= $minPay or p.maxPay>=$minPay) and (p.minPay <= $maxPay or p.maxPay <= $maxPay)";
        } elseif ($salary == '3000~5000元') {
            $minPay = 3000;
            $maxPay = 5000;
            $salarySql = "  and (p.minPay >= $minPay or p.maxPay>=$minPay) and (p.minPay <= $maxPay or p.maxPay <= $maxPay)";
        } elseif ($salary == '5000~8000元') {
            $minPay = 5000;
            $maxPay = 8000;
            $salarySql = "  and (p.minPay >= $minPay or p.maxPay>=$minPay) and (p.minPay <= $maxPay or p.maxPay <= $maxPay)";
        } elseif ($salary == '8000~10000元') {
            $minPay = 8000;
            $maxPay = 10000;
            $salarySql = "  and (p.minPay >= $minPay or p.maxPay>=$minPay) and (p.minPay <= $maxPay or p.maxPay <= $maxPay)";
        } elseif ($salary == '10000元以上') {
            $minPay = 10000;
            $salarySql = "  and (p.minPay >= $minPay or p.maxPay >= $minPay) ";
        } else {
            $salarySql = "";
        }

        if ($education == '初中及以下' || $education == '高中' || $education == '专科' || $education == '本科' ||
            $education == '研究生' || $education == '硕士' || $education == '博士') {
            $educationSql = "  and p.education  = '$education' ";
        } else {
            $educationSql = '';
        }

        if ($workYear == '无经验') {
            $minWorkExp = 0;
            $maxWorkExp = 0;
            $workYearSql = " and p.minWorkExp = $minWorkExp and p.maxWorkExp = $maxWorkExp";
        } elseif ($workYear == '1~3年') {
            $minWorkExp = 1;
            $maxWorkExp = 3;
            $workYearSql = "  and p.minWorkExp >= $minWorkExp and p.maxWorkExp <= $maxWorkExp";
        } elseif ($workYear == '3~5年') {
            $minWorkExp = 3;
            $maxWorkExp = 5;
            $workYearSql = "  and p.minWorkExp >= $minWorkExp and p.maxWorkExp <= $maxWorkExp";
        } elseif ($workYear == '5~10年') {
            $minWorkExp = 5;
            $maxWorkExp = 10;
            $workYearSql = "  and p.minWorkExp >= $minWorkExp and p.maxWorkExp <= $maxWorkExp";
        } elseif ($workYear == '10年以上') {
            $minWorkExp = 10;
            $workYearSql = "  and p.minWorkExp >= $minWorkExp ";
        } else {
            $workYearSql = '';
        }

        if ($isSoldierPriority == 1) {
            $isSoldierPrioritySql = " and p.isSoldierPriority = $isSoldierPriority";
        } else {
            $isSoldierPrioritySql = "";
        }

        if ($labelIds != '') {

            $labelIdsArr = explode(',', $labelIds);

            $labelIdsSql = '';
            foreach ($labelIdsArr as $k => $v) {
                $labelIdsSql .= "   and  p.labelIds like  '%$v%' ";
            }

        } else {
            $labelIdsSql = '';
        }

        if ($province != '') {
            $provinceSql = "  and  zco.province = '$province' ";
        } else {
            $provinceSql = '';
        }
        if ($city != '') {
            $citySql = "   and   zco.city = '$city' ";
        } else {
            $citySql = '';
        }
        if ($area != '') {
            $areaSql = "   and  zco.area = '$area' ";
        } else {
            $areaSql = '';
        }

        $positionModel = new PositionManagementModel();
        list($result, $total) = $positionModel->filter($positionSql, $salarySql, $educationSql, $workYearSql, $isSoldierPrioritySql, $labelIdsSql, $provinceSql, $citySql, $areaSql, $pageIndex, $pageSize);

        foreach ($result as $k => $v) {
            $result[$k]['createDate'] = date('Y-m-d',strtotime($v['createTime']));
            $result[$k]['updateDate'] = date('Y-m-d',strtotime($v['updateTime']));
            $result[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $page['pageIndex'] = $pageIndex;
        $page['pageSize'] = $pageSize;
        $page['total'] = $total;
        $page['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $page);

    }

    public function getPositionPageByCompanyId()
    {
        $params = Request::instance()->request();
        $companyId = Check::checkInteger($params['companyId'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $positionModel = new PositionManagementModel();

        $page = $positionModel->getPageByCompanyId($companyId, $pageIndex, $pageSize);

        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['createDate'] = date('Y-m-d',strtotime($v['createTime']));
            $pageArr[$k]['updateDate'] = date('Y-m-d',strtotime($v['updateTime']));
            $pageArr[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }


}