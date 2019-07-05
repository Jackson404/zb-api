<?php

namespace app\api\controller\v1;

use app\api\model\PositionManagementModel;
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
        $selfEvaluation = Check::check($params['selfEvaluation'] ?? ''); //自我评价
        $militaryTime = Check::check($params['militaryTime'] ?? ''); //入伍时间
        $attendedTime = Check::check($params['attendedTime'] ?? 0); //服役时长
        $corps = Check::check($params['corps'] ?? ''); //兵种


        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $resumeModel = new ResumeModel();
        if ($resumeModel->checkUserHasCreateResume($userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户已经创建过简历');
            exit;
        }

        $data = [
            'name' => $name,
            'userId' => $userId,
            'phone' => $phone,
            'gender' => $gender,
            'age' => $age,
            'workYear' => $workYear,
            'education' => $education,
            'salary' => $salary,
            'skills' => $skills,
            'selfEvaluation' => $selfEvaluation,
            'militaryTime' => $militaryTime,
            'attendedTime' => $attendedTime,
            'corps' => $corps,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

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
        $selfEvaluation = Check::check($params['selfEvaluation'] ?? ''); //自我评价
        $militaryTime = Check::check($params['militaryTime'] ?? ''); //入伍时间
        $attendedTime = Check::check($params['attendedTime'] ?? 0); //服役时长
        $corps = Check::check($params['corps'] ?? ''); //兵种

        $data = [
            'name' => $name,
            'phone' => $phone,
            'gender' => $gender,
            'age' => $age,
            'workYear' => $workYear,
            'education' => $education,
            'salary' => $salary,
            'skills' => $skills,
            'selfEvaluation' => $selfEvaluation,
            'militaryTime' => $militaryTime,
            'attendedTime' => $attendedTime,
            'corps' => $corps,
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

//    public function applyPosition()
//    {
//        $userId = $GLOBALS['userId'];
//        $params = Request::instance()->request();
//        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); // 简历id
//        $positionId = Check::checkInteger($params['positionId'] ?? '');//职位id
//
//        $userApplyPositionModel = new UserApplyPositionModel();
//
//        if ($userApplyPositionModel->checkHasApply($positionId, $resumeId)) {
//            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '已经申请过该职位');
//            exit;
//        }
//
//        $data = [
//            'positionId' => $positionId,
//            'resumeId' => $resumeId,
//            'userId' => $userId,
//            'createTime' => currentTime(),
//            'createBy' => $userId,
//            'updateTime' => currentTime(),
//            'updateBy' => $userId
//        ];
//
//        $insertRow = $userApplyPositionModel->save($data);
//        if ($insertRow > 0) {
//            $arr['id'] = $userApplyPositionModel->id;
//            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
//            exit;
//        } else {
//            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '创建失败');
//            exit;
//        }
//
//    }

    /**
     * 创建并投递简历
     */
    public function addResumeAndApplyPosition()
    {
        $userId = $GLOBALS['userId'];
        $params = Request::instance()->request();

        $positionId = Check::checkInteger($params['positionId'] ?? ''); //职位id

        $name = Check::check($params['name'] ?? '');
        $phone = Check::check($params['phone'] ?? '', 11, 11);
        $gender = Check::checkInteger($params['gender'] ?? 0);// 0 未知 1男 2 女
        $age = Check::checkInteger($params['age'] ?? 0);
        $workYear = Check::checkInteger($params['workYear'] ?? 0); //工作年限
        $education = Check::check($params['education'] ?? '');
        $salary = Check::check($params['salary'] ?? ''); //期望薪资
        $skills = Check::check($params['skills'] ?? ''); //技能描述
        $selfEvaluation = Check::check($params['selfEvaluation'] ?? ''); //自我评价
        $militaryTime = Check::check($params['militaryTime'] ?? ''); //入伍时间
        $attendedTime = Check::check($params['attendedTime'] ?? 0); //服役时长
        $corps = Check::check($params['corps'] ?? ''); //兵种

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $resumeModel = new ResumeModel();
        if ($resumeModel->checkUserHasCreateResume($userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户已经创建过简历');
            exit;
        }

        $data = [
            'name' => $name,
            'userId' => $userId,
            'phone' => $phone,
            'gender' => $gender,
            'age' => $age,
            'workYear' => $workYear,
            'education' => $education,
            'salary' => $salary,
            'skills' => $skills,
            'selfEvaluation' => $selfEvaluation,
            'militaryTime' => $militaryTime,
            'attendedTime' => $attendedTime,
            'corps' => $corps,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $resumeModel->save($data);

        if ($insertRow > 0) {
            $resumeId = $resumeModel->id;

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

            $insertRow2 = $userApplyPositionModel->save($data);

            if ($insertRow2 > 0) {
                $positionModel = new PositionManagementModel();
                $positionModel->updateApplyCountInc($positionId,1);
                $arr['resumeId'] = $resumeId;
                $arr['applyId'] = $userApplyPositionModel->id;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
                exit;
            } else {
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '投递失败');
                exit;
            }

        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '创建失败');
            exit;
        }
    }

    public function applyPosition()
    {
        $userId = $GLOBALS['userId'];
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? '');//职位id

        $resumeModel = new ResumeModel();
        $resumeResult = $resumeModel->getUserResume($userId);

        if ($resumeResult == null) {
            Util::printResult($GLOBALS['ERROR_SQL_QUERY'], '用户简历不存在');
            exit;
        }

        $resumeData = $resumeResult->toArray();
        $resumeId = $resumeData['id'];


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
            $positionModel = new PositionManagementModel();
            $positionModel->updateApplyCountInc($positionId,1);
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
        $listData = $list->toArray();
        $positionModel = new PositionManagementModel();
        foreach ($listData as $k => $v) {
            $positionId = $v['positionId'];
            $positionDetail = $positionModel->getDetailForApply($positionId);
            $listData[$k]['positionDetail'] = $positionDetail;
        }

        $data['total'] = count($listData);
        $data['list'] = $listData;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取简历申请的职位记录
     */
    public function getResumeApplyList()
    {

        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); //简历id
        $userApplyPositionModel = new UserApplyPositionModel();
        $list = $userApplyPositionModel->getResumeApplyList($resumeId);
        $data['list'] = $list;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 分页获取简历申请的职位记录
     */
    public function getResumeApplyPage()
    {
        $params = Request::instance()->request();
        $resumeId = Check::checkInteger($params['resumeId'] ?? ''); //简历id
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $userApplyPositionModel = new UserApplyPositionModel();
        $page = $userApplyPositionModel->getResumeApplyPage($resumeId,$pageIndex,$pageSize);
        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['labelIds'] = json_decode($v['labelIds'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getResumePageByPositionId(){
        $params = Request::instance()->request();
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $userApplyPositionModel = new UserApplyPositionModel();
        $page = $userApplyPositionModel->getResumePageByPositionId($positionId,$pageIndex,$pageSize);
        $data['page'] = $page;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}