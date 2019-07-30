<?php

namespace app\api\model;

use think\Model;

class EpUserLoginHistoryModel extends Model
{
    protected $table = 'zb_enterprise_user_login_history';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function add($data)
    {
        return $this->save($data);
    }

    public function countByIdToken($userId, $token)
    {
        return $this->where('userId', 'eq', $userId)
            ->where('token', 'eq', $token)
            ->where('loginOut', 'eq', 0)
            ->count();
    }
}