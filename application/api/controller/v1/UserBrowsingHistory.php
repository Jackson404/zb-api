<?php

namespace app\api\controller\v1;

use app\api\model\UserBrowsingModel;
use think\Request;
use Check;
use Util;

class UserBrowsingHistory extends IndexBase
{

    /**
     * 添加用户浏览职位的记录
     */
    public function addPositionRecord()
    {
        $params = Request::instance()->param();
        $positionId = Check::checkInteger($params['positionId'] ?? '');
        $userId = $GLOBALS['userId'];

        $userBrowsModel = new UserBrowsingModel();
        $id = $userBrowsModel->checkPositionExist($userId,$positionId);
        if ($id){
            $data = [
                'id'=>$id,
                'userId' => $userId,
                'positionId' => $positionId,
                'updateBy' => $userId,
                'updateTime' => currentTime()
            ];
            $insertRow = $userBrowsModel->isUpdate(true)->save($data);

        }else{
            $data = [
                'userId' => $userId,
                'positionId' => $positionId,
                'createBy' => $userId,
                'createTime' => currentTime(),
                'updateBy' => $userId,
                'updateTime' => currentTime()
            ];
            $insertRow = $userBrowsModel->save($data);
        }
        $arr['insertRow'] = $insertRow;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }


    public function getPositionRecordList(){
        $userId = $GLOBALS['userId'];

        $limit = 5;
        $userBrowsModel = new UserBrowsingModel();

        $positionRecord = $userBrowsModel->getPositionRecord($userId,$limit);
        $data['positionRecord'] = $positionRecord;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }
}