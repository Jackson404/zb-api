<?php

namespace app\api\model;

use think\Model;

class DataResume extends Model
{
    protected $connection = [
        // 数据库类型
        'type' => 'mysql',
        // 服务器地址
        'hostname' => '47.103.102.222',
        // 数据库名
        'database' => 'zhengbu_data',
        // 用户名
        'username' => 'root',
        // 密码
        'password' => 'zhengbu123',
        // 端口
        'hostport' => '3306',
        // 数据库编码默认采用utf8
        'charset' => 'utf8mb4'
    ];
    protected $table = 'data_resume';
    protected $pk = 'resumeId';
    protected $resultSetType = 'collection';

    public function getCount()
    {
        $countSql = "select count(*) from $this->table where isDelete = 0 ";
        return $this->query($countSql);

    }

    public function getByPage($pageIndex, $pageSize)
    {

        $offset = ($pageIndex - 1) * $pageSize;

        $sql = "select resumeId,name,sex,birth,work,wage,profession,position,qua,gra,spe,bonus,allow,resume,phone,mail,habitation,profe,`from` from $this->table where isDelete = 0 order by resumeId desc limit $offset,$pageSize";
        $content = $this->query($sql);

        return $content;
    }

    public function delResume($resumeId)
    {
        return $this->isUpdate(true)
            ->where('resumeId', '=', $resumeId)
            ->update([
                'isDelete' => 1
            ]);
    }

    public function editResume($resumeId,$data)
    {
        return $this->isUpdate(true)
            ->where('resumeId','=',$resumeId)
            ->update($data);
    }

    public function getDetail($resumeId)
    {
        return $this->where('resumeId', '=', $resumeId)
            ->where('isDelete', '=', 0)
            ->find();
    }
}