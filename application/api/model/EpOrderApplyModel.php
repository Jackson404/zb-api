<?php

namespace app\api\model;

use think\Model;

class EpOrderApplyModel extends Model
{
    protected $table = 'zb_enterprise_order_apply';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function getApplyListByOrderId($orderId)
    {
        return $this->alias('py')
            ->join('zb_user u','py.applyUserId=u.id','left')
            ->join('zb_enterprise_user eu','py.shareUserId=eu.id','left')
            ->where('py.orderId','=',$orderId)
            ->where('py.isDelete','=',0)
            ->field('py.id,py.orderId,py.resumeId,py.shareUserId,eu.name as shareUserName,py.applyUserId,
            u.name as applyUserName,u.phone as applyUserPhone,
            py.interviewStatus,py.entryStatus,py.createTime,py.updateTime')
            ->select();
    }

}