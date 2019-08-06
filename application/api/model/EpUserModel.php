<?php

namespace app\api\model;

use think\Model;

class EpUserModel extends Model
{
    protected $table = 'zb_enterprise_user';
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
        return $this->where('phone', '=', $phone)
            ->where('isDelete', '=', 0)
            ->find();
    }

    /**
     * 获取已通过审核的公司的企业用户详情
     * @param $companyName
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDetailByCompanyName($companyName)
    {
        $res = $this->where('companyName', '=', $companyName)
            ->where('isDelete', '=', 0)
            ->where('type','=',1)
            ->where('isReview','=',2)
            ->find();
        return $res;
    }

    public function verifyUserType($userId){
        $res = $this->where('id','=',$userId)
            ->where('isDelete','=',0)
            ->value('type');
        return $res;
    }

}