<?php

namespace app\api\model;

use think\Model;

class EpMsgModel extends Model
{
    protected $table = 'zb_enterprise_msg';
    protected $pk = 'id';
    protected $resultSetType = 'collection';


    protected function getCreateTimeAttr($createTime)
    {
        return date('Y-m-d', strtotime($createTime));  // 时间戳格式改为Y-m-d格式
    }

    protected function getUpdateTimeAttr($updateTime)
    {
        return date('Y-m-d', strtotime($updateTime));  // 时间戳格式改为Y-m-d格式
    }

    public function getListByUserId($userId)
    {
        return $this->alias('m')
            ->join('zb_admin_user au', 'm.sendUserId = au.id', 'left')
            ->join('zb_enterprise_user eu', 'm.recUserId=eu.id', 'left')
            ->where('m.recUserId', '=', $userId)
            ->where('m.isDelete', '=', 0)
            ->field('m.sendUserId,au.name as sendUsername,m.title,m.content,m.recUserId,eu.name as recUsername,m.createTime,m.updateTime')
            ->select();
    }


}