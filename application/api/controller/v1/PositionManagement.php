<?php

namespace app\api\controller\v1;

use app\api\model\CompanyManagementModel;
use app\api\model\PositionManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class PositionManagement extends AdminBase
{
    public function add()
    {

        $params = Request::instance()->request();
        $positionCateId = Check::checkInteger($params['positionCateId'] ?? ''); // 职位类别
        $name = Check::check($params['name'] ?? ''); // 职位名字
        $companyId = Check::checkInteger($params['companyId'] ?? ''); //公司id
        $minPay = Check::checkInteger($params['minPay'] ?? ''); //最低薪资
        $maxPay = Check::checkInteger($params['maxPay'] ?? ''); // 最高薪资
        $minWorkExp = Check::checkInteger($params['minWorkExp'] ?? ''); //最低工作经验
        $maxWorkExp = Check::checkInteger($params['maxWorkExp'] ?? ''); //最高工作经验
        $education = Check::check($params['education'] ?? ''); //学历
        $age = Check::check($params['age'] ?? ''); //年龄
        $num = Check::check($params['num'] ?? ''); //职位招人数
        $labelIds = Check::check($params['labelIds'] ?? ''); //标签
        $isSoldierPriority = Check::checkInteger($params['isSoldierPriority'] ?? 0); //是否军人有限 默认0 0否 1是
        $address = Check::check($params['address'] ?? ''); //公司地址
        $positionRequirement = Check::check($params['positionRequirement'] ?? ''); // 岗位职责
        $positionRequirement = stripslashes($positionRequirement);
        $isShow = Check::checkInteger($params['isShow'] ?? 1); //是否显示 1是 0否

        $userId = $GLOBALS['userId'];

        $positionManagementModel = new PositionManagementModel();

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        if ($positionManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }

        if ($labelIds != '') {
            $labelIdArr = explode(',', $labelIds);
            $labelIdsJson = json_encode($labelIdArr, JSON_UNESCAPED_UNICODE);
        } else {
            $labelIdsJson = json_encode(array());
        }

        if ($minPay == 0 && $maxPay == 0) {
            $pay = 0;
        } else {
            $pay = $minPay . '-' . $maxPay;
        }

        if ($minWorkExp == 0 && $maxWorkExp == 0) {
            $workExp = 0;
        } else {
            $workExp = $minWorkExp . '~' . $maxWorkExp;
        }

        $data = [
            'positionCateId' => $positionCateId,
            'name' => $name,
            'companyId' => $companyId,
            'minPay' => $minPay,
            'maxPay' => $maxPay,
            'pay' => $pay,
            'minWorkExp' => $minWorkExp,
            'maxWorkExp' => $maxWorkExp,
            'workExp' => $workExp,
            'education' => $education,
            'age' => $age,
            'num' => $num,
            'labelIds' => $labelIdsJson,
            'isSoldierPriority' => $isSoldierPriority,
            'address' => $address,
            'positionRequirement' => htmlspecialchars_decode($positionRequirement),
            'isShow' => $isShow,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $positionManagementModel->save($data);
        if ($insertRow > 0) {
            $companyModel = new CompanyManagementModel;
            $result = $companyModel->updatePositionCountInc($companyId, 1);

            $arr['id'] = $positionManagementModel->id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }
    }


    public function edit()
    {

        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id
        $positionCateId = Check::checkInteger($params['positionCateId'] ?? ''); // 职位类别
        $name = Check::check($params['name'] ?? ''); // 职位名字
        $companyId = Check::checkInteger($params['companyId'] ?? ''); //公司id
        $minPay = Check::checkInteger($params['minPay'] ?? ''); //最低薪资
        $maxPay = Check::checkInteger($params['maxPay'] ?? ''); // 最高薪资
        $minWorkExp = Check::checkInteger($params['minWorkExp'] ?? ''); //最低工作经验
        $maxWorkExp = Check::checkInteger($params['maxWorkExp'] ?? ''); //最高工作经验
        $education = Check::check($params['education'] ?? ''); //学历
        $age = Check::check($params['age'] ?? ''); //年龄
        $num = Check::check($params['num'] ?? ''); //职位招人数
        $labelIds = Check::check($params['labelIds'] ?? ''); //标签
        $isSoldierPriority = Check::checkInteger($params['isSoldierPriority'] ?? 0); //是否军人有限 默认0 0否 1是
        $address = Check::check($params['address'] ?? ''); //公司地址
        $positionRequirement = Check::check($params['positionRequirement'] ?? ''); // 岗位职责
        $positionRequirement = stripslashes($positionRequirement);
        $isShow = Check::checkInteger($params['isShow'] ?? 1); //是否显示 1是 0否

        $userId = $GLOBALS['userId'];

        $positionManagementModel = new PositionManagementModel();

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $detail = $positionManagementModel->getDetail($positionId);
        if ($detail['name'] != $name && $positionManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }

        if ($labelIds != '') {
            $labelIdArr = explode(',', $labelIds);
            $labelIdsJson = json_encode($labelIdArr,JSON_UNESCAPED_UNICODE);
        } else {
            $labelIdsJson = json_encode(array());
        }

        if ($minPay == 0 && $maxPay == 0) {
            $pay = 0;
        } else {
            $pay = $minPay . '-' . $maxPay;
        }

        if ($minWorkExp == 0 && $maxWorkExp == 0) {
            $workExp = 0;
        } else {
            $workExp = $minWorkExp . '~' . $maxWorkExp;
        }

        $data = [
            'positionCateId' => $positionCateId,
            'name' => $name,
            'companyId' => $companyId,
            'minPay' => $minPay,
            'maxPay' => $maxPay,
            'pay' => $pay,
            'minWorkExp' => $minWorkExp,
            'maxWorkExp' => $maxWorkExp,
            'workExp' => $workExp,
            'education' => $education,
            'age' => $age,
            'num' => $num,
            'labelIds' => $labelIdsJson,
            'isSoldierPriority' => $isSoldierPriority,
            'address' => $address,
            'positionRequirement' => htmlspecialchars_decode($positionRequirement),
            'isShow' => $isShow,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $oldCompanyId = $detail['companyId'];

        $updateRow = $positionManagementModel->edit($positionId, $data);
        if ($updateRow > 0) {
            $companyModel = new CompanyManagementModel;
            $companyModel->updatePositionCountDec($oldCompanyId, 1);

            $result = $companyModel->updatePositionCountInc($companyId, 1);

            $arr['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '编辑失败');
            exit;
        }
    }

    public function del()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id
        $positionManagementModel = new PositionManagementModel();

        $detail = $positionManagementModel->getDetail($positionId);
        $oldCompanyId = $detail['companyId'];

        $data = [
            'id' => $positionId,
            'isDelete' => 1
        ];

        $delRow = $positionManagementModel->isUpdate(true)->save($data);
        if ($delRow > 0) {
            $companyModel = new CompanyManagementModel;
            $companyModel->updatePositionCountDec($oldCompanyId, 1);
            $arr['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }

    }

    public function getDetail()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id
        $positionManagementModel = new PositionManagementModel();
        $detail = $positionManagementModel->getDetail($positionId);
        $detail['labelIds'] = json_decode($detail['labelIds'], true);
        $data['detail'] = $detail;
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
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        if ($positionCateId != 0) {

            $positionSql = "  and positionCateId= $positionCateId ";
        } else {
            $positionSql = '';
        }

        if ($salary == '3000元以下') {
            $minPay = 0;
            $maxPay = 3000;
            $salarySql = "  and minPay >= $minPay and  maxPay <= $maxPay";
        } elseif ($salary == '3000-5000元') {
            $minPay = 3000;
            $maxPay = 5000;
            $salarySql = "  and minPay >= $minPay and maxPay <= $maxPay";
        } elseif ($salary == '5000-8000元') {
            $minPay = 5000;
            $maxPay = 8000;
            $salarySql = "  and minPay >= $minPay  and  maxPay <= $maxPay";
        } elseif ($salary == '8000-10000元') {
            $minPay = 8000;
            $maxPay = 10000;
            $salarySql = "  and minPay >= $minPay and maxPay <= $maxPay";
        } elseif ($salary == '10000元以上') {
            $minPay = 10000;
            $salarySql = "  and minPay >= $minPay ";
        } else {
            $salarySql = "";
        }

        if ($education == '初中及以下' || $education == '高中' || $education == '专科' || $education == '本科' ||
            $education == '研究生' || $education == '硕士' || $education == '博士') {
            $educationSql = "  and education  = '$education' ";
        } else {
            $educationSql = '';
        }

        if ($workYear == '无经验') {
            $minWorkExp = 0;
            $maxWorkExp = 0;
            $workYearSql = " and minWorkExp = $minWorkExp and maxWorkExp = $maxWorkExp";
        } elseif ($workYear == '1~3年') {
            $minWorkExp = 1;
            $maxWorkExp = 3;
            $workYearSql = "  and minWorkExp >= $minWorkExp and maxWorkExp <= $maxWorkExp";
        } elseif ($workYear == '3~5年') {
            $minWorkExp = 3;
            $maxWorkExp = 5;
            $workYearSql = "  and minWorkExp >= $minWorkExp and maxWorkExp <= $maxWorkExp";
        } elseif ($workYear == '5~10年') {
            $minWorkExp = 5;
            $maxWorkExp = 10;
            $workYearSql = "  and minWorkExp >= $minWorkExp and maxWorkExp <= $maxWorkExp";
        } elseif ($workYear == '10年以上') {
            $minWorkExp = 10;
            $workYearSql = "  and minWorkExp >= $minWorkExp ";
        } else {
            $workYearSql = '';
        }

        if ($isSoldierPriority == 1) {
            $isSoldierPrioritySql = " and isSoldierPriority = $isSoldierPriority";
        } else {
            $isSoldierPrioritySql = "";
        }

        if ($labelIds != '') {

            $labelIdsArr = explode(',', $labelIds);

            $labelIdsSql = '';
            foreach ($labelIdsArr as $k => $v) {
                $labelIdsSql .= "   and  labelIds like  '%$v%' ";
            }

        } else {
            $labelIdsSql = '';
        }

        $positionModel = new PositionManagementModel();
        list($result, $total) = $positionModel->filter($positionSql, $salarySql, $educationSql, $workYearSql, $isSoldierPrioritySql, $labelIdsSql, $pageIndex, $pageSize);


        foreach ($result as $k => $v) {
            $result[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $page['pageIndex'] = $pageIndex;
        $page['pageSize'] = $pageSize;
        $page['total'] = $total;
        $page['page'] = $result;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $page);

    }

    public function isHot()
    {
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $userId = $GLOBALS['userId'];

        $positionModel = new PositionManagementModel();
        $data = [
            'id' => $positionId,
            'isHot' => 1,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];
        $updateRow = $positionModel->isUpdate(true)->save($data);
        $arr['updateRow'] = $updateRow;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    public function getPositionPageByCompanyId()
    {
        $params = Request::instance()->request();
        $companyId = Check::checkInteger($params['companyId'] ?? '');
//        $userId = $GLOBALS['userId'];
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $positionModel = new PositionManagementModel();

        $page = $positionModel->getPageByCompanyId($companyId, $pageIndex, $pageSize);

        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }


}