<?php

namespace app\api\controller\v1\ep;

use app\api\controller\v1\IndexBase;
use app\api\model\EpOrderApplyModel;
use app\api\model\EpOrderModel;
use app\api\model\EpResumeModel;
use app\api\model\ResumeModel;
use think\console\command\make\Model;
use think\Request;
use Util\Check;
use Util\Util;

class EpOrderApply extends IndexBase
{
    /**
     *  用户填写简历，并报名申请该订单上的职位 c端用户申请分享的订单
     */
    public function apply()
    {
        $params = Request::instance()->param();
        $name = Check::check($params['name'] ?? '');
        $phone = Check::check($params['phone'] ?? '', 11, 11);
        $gender = Check::checkInteger($params['gender'] ?? 0);// 0 未知 1男 2 女
        $age = Check::checkInteger($params['age'] ?? 0);
        $workYear = Check::checkInteger($params['workYear'] ?? 0); //工作年限
        $education = Check::check($params['education'] ?? '');
        $skills = Check::check($params['skills'] ?? ''); //技能描述
        $selfEvaluation = Check::check($params['selfEvaluation'] ?? ''); //自我评价
        $militaryTime = Check::check($params['militaryTime'] ?? ''); //入伍时间
        $attendedTime = Check::check($params['attendedTime'] ?? 0); //服役时长
        $corps = Check::check($params['corps'] ?? ''); //兵种
        $exPosition = Check::check($params['exPosition'] ?? ''); //期望职位
        $nature = Check::check($params['nature'] ?? ''); //期望工作性质
        $exCity = Check::check($params['exCity'] ?? ''); //期望城市
        $salary = Check::check($params['salary'] ?? ''); //期望薪资
        $curStatus = Check::check($params['curStatus'] ?? ''); //目前状态
        $arrivalTime = Check::check($params['arrivalTime'] ?? ''); //到岗时间
        $isSoldierPriority = Check::checkInteger($params['isSoldierPriority'] ?? 0); //是否是退役军人 默认0 0否 1是

//        $orderId = Check::checkInteger($params['orderId'] ?? '');
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $shareUserId = Check::checkInteger($params['shareUserId'] ?? '');
        $applyUserId = $GLOBALS['userId'];

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $resumeModel = new ResumeModel();
        if ($resumeModel->checkUserHasCreateResume($applyUserId)) {
            $resume = $resumeModel->getUserResume($applyUserId);
            $resumeData = $resume->toArray();
            $resumeId = $resumeData['id'];
        } else {
            $data = [
                'name' => $name,
                'userId' => $applyUserId,
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
                'exPosition' => $exPosition,
                'nature' => $nature,
                'exCity' => $exCity,
                'curStatus' => $curStatus,
                'arrivalTime' => $arrivalTime,
                'isSoldierPriority' => $isSoldierPriority,
                'isOpen' => 0,
                'createTime' => currentTime(),
                'createBy' => $applyUserId,
                'updateTime' => currentTime(),
                'updateBy' => $applyUserId
            ];

            $resumeModel->startTrans();
            $insertRow = $resumeModel->save($data);

            if ($insertRow > 0) {
                $resumeId = $resumeModel->getLastInsID();
            } else {
                $resumeModel->rollback();
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '简历创建失败');
                exit;
            }
        }

        $epOrderModel = new EpOrderModel();

        $orderDetail = $epOrderModel->verifyUserRecOrderDetail($positionId,$shareUserId);
        if ($orderDetail == null){
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '该订单不存在');
            exit;
        }
        $orderId = $orderDetail->orderId;

        $epOrderApplyModel = new EpOrderApplyModel();

        if ($epOrderApplyModel->checkUserHasApply($orderId, $applyUserId) > 0) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户已经申请过该订单');
            exit;
        }


        $arr = [
            'orderId' => $orderId,
            'resumeId' => $resumeId,
            'shareUserId' => $shareUserId,
            'applyUserId' => $applyUserId,
            'createTime' => currentTime(),
            'updateTime' => currentTime()
        ];

        $res = $epOrderApplyModel->save($arr);
        if ($res > 0) {
            $epOrderModel = new EpOrderModel();
            $xx = $epOrderModel->incApplyNum($orderId, 1);
        } else {
            $resumeModel->rollback();
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '申请失败');
            exit;
        }

        $epResumeModel = new EpResumeModel();

        if ($epResumeModel->resumeExistsSource1($applyUserId, $resumeId)) {
            $resumeModel->commit();
            $x['insertRow'] = $res;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $x);
            exit;
        } else {
            $arr = [
                'userId' => $shareUserId,
                'resumeId' => $resumeId,
                'source' => 1,
                'createTime' => currentTime(),
                'createBy' => $shareUserId,
                'updateTime' => currentTime(),
                'updateBy' => $shareUserId
            ];

            $insertRow = $epResumeModel->save($arr);

            if ($insertRow > 0) {
                $resumeModel->commit();
                $x['insertRow'] = $insertRow;
                Util::printResult($GLOBALS['ERROR_SUCCESS'], $x);
                exit;
            } else {
                $resumeModel->rollback();
                Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '申请失败');
                exit;
            }
        }


    }


}