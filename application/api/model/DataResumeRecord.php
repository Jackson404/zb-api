<?php

namespace app\api\model;

use think\Exception;
use think\Model;

class DataResumeRecord extends Model
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
    protected $table = 'zb_resume_record';
    protected $pk = ['idCard', 'phone'];
    protected $resultSetType = 'collection';

    public function addRecordX($data){
        return  $this->insertGetId($data);
    }

    public function updateRecord($recordId,$data){
        return $this->where('id','=',$recordId)
            ->update($data);
    }

    public function addRecord($data, $filterData)
    {
        $this->startTrans();
        try {
            $recordId = $this->insert($data);
            if ($recordId <= 0) {
                $this->rollback();
                return -1;
            }
            $arr = [];
            foreach ($filterData as $k => $v) {
                $idCard = $v['idCard'];
                $phone = $v['phone'];
                $updateRow = $this->table('zb_resume_new')->where('idCard', '=', $idCard)
                    ->where('phone', '=', $phone)
                    ->where('isDelete', '=', 0)
                    ->update(['recordId' => $recordId, 'updateTime' => date('Y-m-d', time())]);
                if ($updateRow > 0) {
                    $this->commit();
                    array_push($arr, $updateRow);
                } else {
                    $this->rollback();
                    return -1;
                }
            }
            return $recordId;

        } catch (Exception $e) {
            $this->rollback();
//            var_dump($e->getMessage());
            return -1;
        }
    }

    public function getRecordListByDate($date)
    {
        return $this->whereOr('createTime', '=', $date)
            ->whereOr('updateTime','=',$date)
            ->where('isDelete','=',0)
            ->select();
    }


}