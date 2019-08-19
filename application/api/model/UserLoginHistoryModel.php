<?php

namespace app\api\model;

use think\Model;

class UserLoginHistoryModel extends Model
{
    protected $name = 'user_login_history';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function countByIdToken($userId, $token)
    {
        return $this->where('userId', 'eq', $userId)
            ->where('token', 'eq', $token)
            ->where('loginOut', 'eq', 0)->count();
    }
}