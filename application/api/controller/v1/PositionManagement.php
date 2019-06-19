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
        $age = Check::checkInteger($params['age'] ?? 0); //年龄
        $num = Check::checkInteger($params['num'] ?? ''); //职位招人数
        $labelIds = Check::check($params['labelIds'] ?? ''); //标签
        $isSoldierPriority = Check::checkInteger($params['isSoldierPriority'] ?? 0); //是否军人有限 默认0 0否 1是
        $address = Check::check($params['address'] ?? ''); //公司地址
        $positionRequirement = Check::check($params['positionRequirement'] ?? ''); // 岗位职责
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

        $labelIdArr = explode(',', $labelIds);
        $labelIdsJson = json_encode($labelIdArr);

        $data = [
            'positionCateId' => $positionCateId,
            'name' => $name,
            'companyId' => $companyId,
            'minPay' => $minPay,
            'maxPay' => $maxPay,
            'minWorkExp' => $minWorkExp,
            'maxWorkExp' => $maxWorkExp,
            'education' => $education,
            'age' => $age,
            'num' => $num,
            'labelIds' => $labelIdsJson,
            'isSoldierPriority' => $isSoldierPriority,
            'address' => $address,
            'positionRequirement' => $positionRequirement,
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
        $age = Check::checkInteger($params['age'] ?? 0); //年龄
        $num = Check::checkInteger($params['num'] ?? ''); //职位招人数
        $labelIds = Check::check($params['labelIds'] ?? ''); //标签
        $isSoldierPriority = Check::checkInteger($params['isSoldierPriority'] ?? 0); //是否军人有限 默认0 0否 1是
        $address = Check::check($params['address'] ?? ''); //公司地址
        $positionRequirement = Check::check($params['positionRequirement'] ?? ''); // 岗位职责
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

        $labelIdArr = explode(',', $labelIds);
        $labelIdsJson = json_encode($labelIdArr);

        $data = [
            'positionCateId' => $positionCateId,
            'name' => $name,
            'companyId' => $companyId,
            'minPay' => $minPay,
            'maxPay' => $maxPay,
            'minWorkExp' => $minWorkExp,
            'maxWorkExp' => $maxWorkExp,
            'education' => $education,
            'age' => $age,
            'num' => $num,
            'labelIds' => $labelIdsJson,
            'isSoldierPriority' => $isSoldierPriority,
            'address' => $address,
            'positionRequirement' => $positionRequirement,
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
        foreach ($list as $k=>$v){
            $list[$k]['labelIds'] = json_decode($v['labelIds'],true);
        }

        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);

    }


}