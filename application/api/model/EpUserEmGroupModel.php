<?php

namespace app\api\model;

use think\Model;

class EpUserEmGroupModel extends Model
{
    protected $table = 'zb_enterprise_em_group';
    protected $pk = 'groupId';
    protected $resultSetType = 'collection';

    public function getAllByEpUserId($epUserId)
    {
        $res = $this->where('epUserId', '=', $epUserId)
            ->where('isDelete', '=', 0)
            ->order('groupId', 'desc')
            ->select();
        return $res->toArray();
    }

    public function verifyCreatePermission($groupId, $epUserId)
    {
        $x = $this->where('groupId', '=', $groupId)
            ->where('isDelete', '=', 0)
            ->value('epUserId');
        if ($x == $epUserId) {
            return true;
        } else {
            return false;
        }
    }



}