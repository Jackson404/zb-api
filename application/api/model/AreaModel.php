<?php

namespace app\api\model;

use think\Model;

class AreaModel extends Model
{
    protected $table = 'zb_area';
    protected $resultSetType = 'collection';

    public function getProvince()
    {
        $sql = "select distinct province from zb_area";
        return $this->query($sql);
    }

    public function getCity($province)
    {
        $sql = "select distinct city from zb_area where province = '$province'";
        return $this->query($sql);
    }

    public function getArea($city)
    {
        $sql = "select distinct area from zb_area where city = '$city'";
        return $this->query($sql);
    }

}