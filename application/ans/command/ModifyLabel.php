<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ModifyLabel extends Command
{
    protected function configure()
    {
        $this->setName('label');
    }

    protected function execute(Input $input, Output $output)
    {
        $oldTag = Db::connect($GLOBALS['db_config'])->name('tag')->select();

        foreach ($oldTag as $k => $v) {
            $arr['name'] = $v['name'];

            $arr['createTime'] = date('Y-m-d H:i:s', $v['time']);
            $arr['createBy'] = 1;
            $arr['updateTime'] = date('Y-m-d H:i:s', $v['time']);
            $arr['updateBy'] = 1;

            $insertId = Db::table('zb_label_management')->insert($arr);
            var_dump($insertId);
        }
    }
}