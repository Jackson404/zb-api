<?php

namespace app\api\model;

use think\Model;

class EpCertModel extends Model
{
    protected $table = 'zb_enterprise_cert';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    public function getByUserIdAndType($userId,$type){
        return $this->where('userId','=',$userId)
            ->where('type','=',$type)
            ->where('isDelete','=',0)
            ->find();
    }
}