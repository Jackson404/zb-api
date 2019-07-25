<?php

namespace app\api\model;

use think\Model;

class DataResume extends Model
{
    protected $connection = [
        'type' => 'mysql',
        // 服务器地址
        'hostname' => 'zhengbu123.mysql.rds.aliyuncs.com',
        // 数据库名
        'database' => 'resume_data',
        // 用户名
        'username' => 'zhengbu',
        // 密码
        'password' => 'Zhengbu123',
        // 端口
        'hostport' => '3306'
    ];
    protected $table = 'zb_resume_new';
    protected $pk = ['idCard', 'phone'];
    protected $resultSetType = 'collection';

    public function getCount()
    {
        $countSql = "select count(*) from $this->table where isDelete = 0 ";
        $res = $this->query($countSql);
        $count = $res[0]["count(*)"];
        return $count;

    }

    public function getByPage($pageIndex, $pageSize)
    {

        $offset = ($pageIndex - 1) * $pageSize;

        $sql = "select idCard,phone,name,sex,birthYear,birth,school,education,educationName,mail,profession,professionId,workYear,
                exPosition,exSalary,exCity,habitation,houseLocation,workUnit,createTime,deliveryTime,`from`,type  from 
                $this->table where isDelete=0  order by idCard,phone desc limit $offset,$pageSize";
        $content = $this->query($sql);

        return $content;
    }

    public function getByPageRecordId($recordId, $pageIndex, $pageSize)
    {

        $offset = ($pageIndex - 1) * $pageSize;

        $sql = "select idCard,phone,name,sex,birthYear,birth,school,education,educationName,mail,profession,professionId,workYear,
                exPosition,exSalary,exCity,habitation,houseLocation,workUnit,createTime,deliveryTime,`from`,type  from 
                $this->table where recordId=$recordId and isDelete=0  order by idCard,phone desc limit $offset,$pageSize";
        $content = $this->query($sql);

        return $content;
    }

    public function getCountByRecordId($recordId)
    {

        $sql = "select count(*)  from 
                $this->table where recordId=$recordId and isDelete=0";
        $res = $this->query($sql);
        $count = $res[0]["count(*)"];
        return $count;
    }


    public function filterByPage($posKeySql, $exWorkLocationSql, $workExpSql, $educationNameSql, $minAgeSql, $maxAgeSql, $sexSql, $pageIndex, $pageSize)
    {

        $offset = ($pageIndex - 1) * $pageSize;

        $sql = "select idCard,phone,name,sex,birthYear,birth,school,education,educationName,mail,profession,professionId,workYear,
                exPosition,exSalary,exCity,habitation,houseLocation,workUnit,createTime,deliveryTime,`from`,type  from 
                $this->table where isDelete=0 $posKeySql $exWorkLocationSql $workExpSql  $educationNameSql  $minAgeSql $maxAgeSql  $sexSql  order by idCard,phone desc limit $offset,$pageSize";
        $content = $this->query($sql);

        return $content;
    }

    public function filterCount($posKeySql, $exWorkLocationSql, $workExpSql, $educationNameSql, $minAgeSql, $maxAgeSql, $sexSql)
    {

        $sql = "select count(*)  from 
                $this->table where isDelete=0 $posKeySql $exWorkLocationSql $workExpSql  $educationNameSql  $minAgeSql $maxAgeSql  $sexSql ";
        $res = $this->query($sql);
        $count = $res[0]["count(*)"];
        return $count;
    }

    public function star($idCard, $phone,$type)
    {
        $data = [
            'type' => $type,
            'updateTime' => date('Y-m-d', time())
        ];
        $updateRow = $this->where('idCard', '=', $idCard)->where('phone', '=', $phone)
            ->where('isDelete', '=', 0)->update($data);
        return $updateRow;
    }

    public function del($idCard, $phone)
    {
        $delRow = $this->where('idCard', '=', $idCard)->where('phone', '=', $phone)
            ->where('isDelete', '=', 0)->delete();
        return $delRow;
    }

    public function detail($idCard, $phone)
    {
        return $this->where('idCard', '=', $idCard)->where('phone', '=', $phone)
            ->where('isDelete', '=', 0)->find();
    }


}