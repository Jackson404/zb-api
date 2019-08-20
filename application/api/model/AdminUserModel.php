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
        $count = $this->where('name','=', $username)->count();
        return $count;
    }

    /**
     * 根据用户名字获取用户信息
     * @param $username
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserInfoByName($username)
    {
        return $this->where('name', '=', $username)
            ->where('isDelete', '=', 0)
            ->find();
    }
}