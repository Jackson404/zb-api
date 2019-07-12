<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ModifyCompany extends Command
{
    protected function configure()
    {
        $this->setName('company');
    }

    protected function execute(Input $input, Output $output)
    {
        $oldCompany = Db::connect($GLOBALS['db_config'])->name('company')->select();
        foreach ($oldCompany as $k => $v) {
            $arr['id'] = $v['c_id'];
            $arr['name'] = $v['name'];
            $arr['address'] = $v['address'];
            $arr['phone'] = $v['phone'];
            $arr['nature'] = $v['xz'];
            $arr['profile'] = $v['des'];
            $arr['createTime'] = date('Y-m-d H:i:s', $v['time']);
            $arr['createBy'] = 1;
            $arr['updateTime'] = date('Y-m-d H:i:s', $v['time']);
            $arr['updateBy'] = 1;

            $insertId = Db::table('zb_company_management')->insert($arr);
            var_dump($insertId);
        }
    }
}