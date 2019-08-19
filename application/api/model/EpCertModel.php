<?php

namespace app\api\model;

use think\Model;

class EpCertModel extends Model
{
    protected $table = 'zb_enterprise_cert';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function getByUserIdAndType($userId, $type)
    {
        return $this->where('userId', '=', $userId)
            ->where('type', '=', $type)
            ->where('isDelete', '=', 0)
            ->find();
    }

    /**
     * 删除员工时 清空认证表中信息
     * @param $emUserId
     * @return EpCertModel
     */
    public function delEmUserCertInfo($emUserId)
    {
        return $this->where('userId', '=', $emUserId)
            ->where('isDelete', '=', 0)
            ->update(['isDelete' => 1]);
    }
}