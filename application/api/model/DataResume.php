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

    public function getByPage($pageIndex, $pageSize)
    {
        $config = [
            'list_rows' => $pageSize,
            'page' => $pageIndex
        ];

        return $this->where('isDelete', '=', 0)
            ->order('resumeId', 'desc')
            ->paginate(null, false, $config);

    }
}