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

    public function getDetailByCompanyName($companyName)
    {
        $res = $this->where('companyName', '=', $companyName)
            ->where('isDelete', '=', 0)
            ->find();
        return $res->toArray();
    }

    public function verifyUserType($userId){
        $res = $this->where('id','=',$userId)
            ->where('isDelete','=',0)
            ->value('type');
        return $res;
    }

}