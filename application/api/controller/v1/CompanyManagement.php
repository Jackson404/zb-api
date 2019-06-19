<?php

namespace app\api\controller\v1;

use app\api\model\CompanyManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class CompanyManagement extends AdminBase
{
    public function add()
    {
        $params = Request::instance()->request();
        $name = Check::check($params['name'] ?? '');
        $address = Check::check($params['address'] ?? '');
        $phone = Check::check($params['phone'] ?? '', 0, 11);
        $nature = Check::check($params['nature'] ?? ''); // 公司性质
        $profile = Check::check($params['profile'] ?? ''); //公司简介
        $remark = Check::check($params['remark'] ?? '');
        $userId = $GLOBALS['userId'];

        $companyManagementModel = new CompanyManagementModel();

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        if ($companyManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }

        $data = [
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'nature' => $nature,
            'profile' => $profile,
            'remark' => $remark,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $companyManagementModel->save($data);
        if ($insertRow > 0) {
            $arr['id'] = $companyManagementModel->id;
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
        $companyId = Check::checkInteger($params['companyId'] ?? '');
        $name = Check::check($params['name'] ?? '');
        $address = Check::check($params['address'] ?? '');
        $phone = Check::check($params['phone'] ?? '', 0, 11);
        $nature = Check::check($params['nature'] ?? ''); // 公司性质
        $profile = Check::check($params['profile'] ?? ''); //公司简介
        $remark = Check::check($params['remark'] ?? '');
        $userId = $GLOBALS['userId'];

        $companyManagementModel = new CompanyManagementModel();
        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $detail = $companyManagementModel->getDetail($companyId);

        if ($detail['name'] != $name && $companyManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }
        $data = [
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'nature' => $nature,
            'profile' => $profile,
            'remark' => $remark,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $updateRow = $companyManagementModel->edit($companyId, $data);
        if ($updateRow > 0) {
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
        $companyId = Check::checkInteger($params['companyId'] ?? '');
        $companyManagementModel = new CompanyManagementModel();
        $data = [
            'id' => $companyId,
            'isDelete' => 1
        ];
        $delRow = $companyManagementModel->isUpdate(true)->save($data);
        if ($delRow > 0) {
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
        $companyId = Check::checkInteger($params['companyId'] ?? '');

        $companyManagementModel = new CompanyManagementModel();
        $detail = $companyManagementModel->getDetail($companyId);

        $arr['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    public function getByPage()
    {
        $params = Request::instance()->request();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $companyManagementModel = new CompanyManagementModel();
        $page = $companyManagementModel->getByPage($pageIndex, $pageSize);

        $arr['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }
}