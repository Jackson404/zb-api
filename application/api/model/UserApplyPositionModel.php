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

    public function getResumeApplyPage($resumeId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('aly')
            ->join('zb_position_management p','aly.positionId = p.id')
            ->join('zb_category_management zcm','p.positionCateId = zcm.id')
            ->join('zb_company_management zco','p.companyId = zco.id')
            ->where('aly.isDelete', '=', 0)
            ->where('aly.resumeId', '=', $resumeId)
            ->field('aly.id,aly.positionId,p.positionCateId,zcm.name as positionCateName,p.name,p.companyId,zco.name as companyName,
            p.minPay,p.maxPay,p.pay,p.minWorkExp,p.maxWorkExp,p.workExp,p.education,p.age,p.num,p.labelIds,p.isSoldierPriority,p.address,
            p.positionRequirement,p.isShow,p.applyCount,p.createTime,p.createBy,p.updateTime,p.updateBy')
            ->order('aly.id','desc')
            ->paginate(null, false, $config);
    }

    public function getResumePageByPositionId($positionId, $pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];
        return $this->alias('aly')
            ->join('zb_resume r','aly.resumeId = r.id')
            ->where('aly.isDelete', '=', 0)
            ->where('aly.positionId', '=', $positionId)
            ->order('aly.id','desc')
            ->paginate(null, false, $config);
    }
}