<?php

namespace app\api\model;

use think\Model;

class AdminUserModel extends Model
{
    protected $name = 'admin_user';
    protected $pk = 'id';

    /**
     * 验证用户名
     * @param $username
     * @return int|string
     * @throws \think\Exception
     */
    public function verifyUsername($username)
    {
        $count = $this->where('name', $username)->count();
        return $count;
    }

    /**
     * 根据用户名获取密码
     * @param $username
     * @return mixed
     */
    public function getPasswordByName($username)
    {
        return $this->where('name', $username)->value('password');
    }

    /**
     * 根据用户名获取用户id
     * @param $username
     * @return mixed
     */
    public function getIdByName($username){
        return $this->where('name',$username)->value('id');
    }

}