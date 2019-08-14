<?php

namespace app\api\controller\v1;

use app\api\model\UserPositionIntensionModel;
use think\Request;
use Util\Check;
use Util\Util;

class UserPositionIntension extends IndexBase
{
    public function add()
    {
        $params = Request::instance()->param();
        $exPosition = Check::check($params['exPosition'] ?? ''); //期望职位
        $nature = Check::check($params['nature'] ?? ''); //期望工作性质
        $exCity = Check::check($params['exCity'] ?? ''); //期望城市
        $exSalary = Check::check($params['exSalary'] ?? ''); //期望薪资
        $curStatus = Check::check($params['curStatus'] ?? ''); //目前状态
        $arrivalTime = Check::check($params['arrivalTime'] ?? ''); //到岗时间
        $userId = $GLOBALS['userId'];
        $userIntensionModel = new UserPositionIntensionModel();
        if ($userIntensionModel->checkExist($userId)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '求职意向,已经存在');
            exit;
        }
        $data = [
            'userId' => $userId,
            'exPosition' => $exPosition,
            'nature' => $nature,
            'exCity' => $exCity,
            'exSalary' => $exSalary,
            'curStatus' => $curStatus,
            'arrivalTime' => $arrivalTime,
            'createBy' => $userId,
            'createTime' => currentTime(),
            'updateBy' => $userId,
            'updateTime' => currentTime()
        ];


        $insertRow = $userIntensionModel->save($data);
        $arr['inserRow'] = $insertRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    public function edit()
    {
        $params = Request::instance()->param();

        $exPosition = Check::check($params['exPosition'] ?? ''); //期望职位
        $nature = Check::check($params['nature'] ?? ''); //期望工作性质
        $exCity = Check::check($params['exCity'] ?? ''); //期望城市
        $exSalary = Check::check($params['exSalary'] ?? ''); //期望薪资
        $curStatus = Check::check($params['curStatus'] ?? ''); //目前状态
        $arrivalTime = Check::check($params['arrivalTime'] ?? ''); //到岗时间
        $userId = $GLOBALS['userId'];
        $userIntensionModel = new UserPositionIntensionModel();
        $positionIntension = $userIntensionModel->getPositionIntensionByUserId($userId);
        if ($positionIntension == null) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '用户没有创建求职意向');
            exit;
        }
        $positionIntensionData = $positionIntension->toArray();
        $positionIntensionId = $positionIntensionData['id'];

        $data = [
            'id' => $positionIntensionId,
            'exPosition' => $exPosition,
            'nature' => $nature,
            'exCity' => $exCity,
            'exSalary' => $exSalary,
            'curStatus' => $curStatus,
            'arrivalTime' => $arrivalTime,
            'updateBy' => $userId,
            'updateTime' => currentTime()
        ];


        $updateRow = $userIntensionModel->isUpdate(true)->save($data);
        $arr['updateRow'] = $updateRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    public function getPositionIntension()
    {
        $userId = $GLOBALS['userId'];
        $userIntensionModel = new UserPositionIntensionModel();
        $detail = $userIntensionModel->getPositionIntensionByUserId($userId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}