<?php

namespace app\api\model;

use think\Model;

class UserModel extends Model
{
    protected $name = 'user';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function checkPhoneExist($phone)
    {
        $count = $this->where('isDelete', '=', 0)->where('phone', '=', $phone)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getByPhone($phone)
    {
        return $this->where('phone', '=', $phone)->where('isDelete', '=', 0)->find();
    }

    public function getUserInfoByUserId($userId)
    {
        return $this->where('id', '=', $userId)
            ->where('isDelete', '=', 0)
            ->field('id,avatar,name,phone')
            ->find();
    }
}