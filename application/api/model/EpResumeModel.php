<?php
namespace app\api\model;

use think\Model;

class EpResumeModel extends Model{
    protected $table = 'zb_enterprise_resume';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function getDownloadNumOneDay($date,$userId){
        $count  = $this->where('userId','=',$userId)
            ->where('createTime','like',"$date%")
            ->where('isDelete','=',0)
            ->count();
        return $count;
    }

    public function  getListByUserId($userId){
        return $this->where('userId','=',$userId)
            ->where('isDelete','=',0)
            ->select();
    }
}