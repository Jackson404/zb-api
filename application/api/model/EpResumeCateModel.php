<?php
namespace app\api\model;

use think\Model;

class EpResumeCateModel extends Model{
    protected $table = 'zb_enterprise_resume_cate';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function getResumeCateListByUserId($userId){
        return $this->where('userId','=',$userId)
            ->where('isDelete','=',0)
            ->select();
    }

}