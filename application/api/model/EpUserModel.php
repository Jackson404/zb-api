<?php

namespace app\api\model;

use think\Model;

class EpUserModel extends Model
{
    protected $table = 'zb_enterprise_user';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function checkPhoneExist($phone){
        $count = $this->where('isDelete', '=', 0)->where('phone', '=', $phone)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getByPhone($phone){
        return $this->where('phone','=',$phone)
            ->where('isDelete','=',0)
            ->find();
    }

}