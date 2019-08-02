<?php

namespace app\api\model;

use think\Model;

class EpOrderModel extends Model
{
    protected $table = 'zb_enterprise_order';
    protected $pk = 'id';
    protected $resultSetType = 'collection';

    /**
     * 添加接单
     * @param $data
     * @return int|string
     */
    public function add($data){
        return $this->insertGetId($data);
    }

}