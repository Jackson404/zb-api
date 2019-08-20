<?php
namespace app\api\model;

use think\Model;

class UserPositionIntensionModel extends Model{
    protected $table = 'zb_user_position_intension';
    protected $resultSetType = 'collection';

    public function checkExist($userId){
        $count = $this->where('userId','=',$userId)
            ->where('isDelete','=',0)
            ->count();
        if ($count>0){
            return true;
        }else{
            return false;
        }
    }

    public function getPositionIntensionByUserId($userId){
        return $this->where('userId','=',$userId)
            ->where('isDelete','=',0)
            ->find();
    }

}
