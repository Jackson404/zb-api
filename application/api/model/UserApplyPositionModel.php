<?php

namespace app\api\model;

use think\Model;

class UserApplyPositionModel extends Model
{
    protected $name = 'user_apply_position';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function checkHasApply($positionId, $resumeId)
    {
        $count = $this->where('isDelete', '=', 0)
            ->where('positionId', '=', $positionId)
            ->where('resumeId', '=', $resumeId)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserApplyList($userId)
    {
        return $this->where('isDelete', '=', 0)->where('userId', '=', $userId)
            ->select();
    }

    public function getResumeApplyList($resumeId)
    {
        return $this->where('isDelete', '=', 0)->where('resumeId', '=', $resumeId)
            ->select();
    }
}