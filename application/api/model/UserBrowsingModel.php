<?php

namespace app\api\model;

use think\Model;

class UserBrowsingModel extends Model
{
    protected $table = 'zb_user_browsing_history';
    protected $resultSetType = 'collection';

    public function checkPositionExist($userId, $positionId)
    {
        $result = $this->where('isDelete', '=', 0)
            ->where('userId', '=', $userId)
            ->where('positionId', '=', $positionId)
            ->find();

        if ($result != null) {
            $data = $result->toArray();
            $id = $data['id'];
            if ($id > 0) {
                return $id;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getPositionRecordDetail($recordId)
    {
        return $this->where('id', '=', $recordId)->where('isDelete', '=', 0)->find();
    }

    public function getPositionRecord($userId, $limit)
    {
        return $this->alias('r')
            ->join('zb_position_management p', 'r.positionId = p.id')
            ->join('zb_company_management c','p.companyId = c.id')
            ->where('r.userId', '=', $userId)
            ->field('p.id,p.name,p.companyId,c.name as companyName,p.pay,
            r.createTime,r.createBy,r.updateTime,r.updateBy')
            ->where('r.isDelete', '=', 0)
            ->order('updateTime','desc')
            ->limit(0, $limit)
            ->select();
    }
}