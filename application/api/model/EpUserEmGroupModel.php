<?php

namespace app\api\model;

use think\exception\PDOException;
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

    public function delGroup($groupId)
    {
        $this->startTrans();
        try {
            $updateRow = $this->where('groupId', '=', $groupId)
                ->where('isDelete', '=', 0)
                ->update(['isDelete' => 1]);

            if ($updateRow > 0) {
                $up = $this->table('zb_enterprise_cert_review')
                    ->where('groupId', '=', $groupId)
                    ->where('isDelete', '=', 0)
                    ->update(['groupId' => 0]);
                $this->commit();
                return $updateRow;
            } else {
                $this->rollback();
                return -2;
            }
        } catch (PDOException $e) {
            $this->rollback();
            return -1;
        }


    }


}