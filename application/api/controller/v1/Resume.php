<?php

namespace app\api\controller\v1;

use app\api\model\ResumeModel;
use app\api\model\UserApplyPositionModel;
use think\Request;
use Util\Check;
use Util\Util;

class Resume extends IndexBase
{
    /**
     * 创建简历
     */
    public function add()
    {
        $userId = $GLOBALS['userId'];
        $params = Request::instance()->request();
        $name = Check::check($params['name'] ?? '');
        $phone = Check::check($params['phone'] ?? '', 11, 11);
        $gender = Check::checkInteger($params['gender'] ?? 0);// 0 未知 1男 2 女
        $age = Check::checkInteger($params['age'] ?? 0);
        $workYear = Check::checkInteger($params['workYear'] ?? 0); //工作年限
        $education = Check::check($params['education'] ?? '');
        $salary = Check::check($params['salary'] ?? ''); //期望薪资
        $skills = Check::check($params['skills'] ?? ''); //技能描述

        if ($name == ''){
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'],'缺少参数');
            exit;
        }

        $data = [
            'name' => $name,
            'phone' => $phone,
            'gender' => $gender,
            'age' => $age,
            'workYear' => $workYear,
            'education' => $education,
            'salary' => $salary,
            'skills' => $skills,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $resumeModel = new ResumeModel();

        $insertRow = $resumeModel->save($data);

        if ($insertRow > 0) {
            $arr['id'] = $resumeModel->id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '创建失败');
            exit;
        }
    }

    public function edit()
    {
        $userId = $GLOBALS['userId'];
        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); // 简历id
        $name = Check::check($params['name'] ?? '');
        $phone = Check::check($params['phone'] ?? '', 11, 11);
        $gender = Check::checkInteger($params['gender'] ?? 0);// 0 未知 1男 2 女
        $age = Check::checkInteger($params['age'] ?? 0);
        $workYear = Check::checkInteger($params['workYear'] ?? 0); //工作年限
        $education = Check::check($params['education'] ?? '');
        $salary = Check::check($params['salary'] ?? ''); //期望薪资
        $skills = Check::check($params['skills'] ?? ''); //技能描述

        $data = [
            'name' => $name,
            'phone' => $phone,
            'gender' => $gender,
            'age' => $age,
            'workYear' => $workYear,
            'education' => $education,
            'salary' => $salary,
            'skills' => $skills,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $resumeModel = new ResumeModel();

        $updateRow = $resumeModel->edit($resumeId, $data);

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
        $userId = $GLOBALS['userId'];
        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); // 简历id

        $resumeModel = new ResumeModel();

        $delRow = $resumeModel->del($resumeId, $userId);

        $data['delRow'] = $delRow;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getByPage()
    {
        $params = Request::instance()->request();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $resumeModel = new ResumeModel();
        $page = $resumeModel->getByPage($pageIndex, $pageSize);
        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getResumeByUserId()
    {
        $userId = $GLOBALS['userId'];
        $resumeModel = new ResumeModel();
        $list = $resumeModel->getByUserId($userId);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getDetail()
    {
        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); // 简历id
        $resumeModel = new ResumeModel();
        $detail = $resumeModel->getDetail($resumeId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function applyPosition()
    {
        $userId = $GLOBALS['userId'];
        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); // 简历id
        $positionId = Check::checkInteger($params['positionId'] ?? '');//职位id

        $userApplyPositionModel = new UserApplyPositionModel();

        if ($userApplyPositionModel->checkHasApply($positionId, $resumeId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '已经申请过该职位');
            exit;
        }

        $data = [
            'positionId' => $positionId,
            'resumeId' => $resumeId,
            'userId' => $userId,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $userApplyPositionModel->save($data);
        if ($insertRow > 0) {
            $arr['id'] = $userApplyPositionModel->id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '创建失败');
            exit;
        }

    }

    /**
     * 获取用户的投递记录
     */
    public function getUserApplyList()
    {
        $userId = $GLOBALS['userId'];
        $userApplyPositionModel = new UserApplyPositionModel();
        $list = $userApplyPositionModel->getUserApplyList($userId);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取简历申请的职位记录
     */
    public function getUserResumeApplyList()
    {

        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); //简历id
        $userApplyPositionModel = new UserApplyPositionModel();
        $list = $userApplyPositionModel->getResumeApplyList($resumeId);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}