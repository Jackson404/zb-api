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

    public function areaInfo()
    {
        $sql1 = "select distinct province from zb_area";
        $sql2 = "select distinct city from zb_area";
        $sql3 = "select distinct area from zb_area";
        $res1 = $this->query($sql1);
        $res2 = $this->query($sql2);
        $res3 = $this->query($sql3);
        return [$res1, $res2, $res3];
    }
}