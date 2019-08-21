<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ModifyBar extends Command
{
    protected function configure()
    {
        $this->setName('bar');
    }

    protected function execute(Input $input, Output $output)
    {
        $oldBar = Db::connect($GLOBALS['db_config'])->name('bar')->select();

        foreach ($oldBar as $k => $v) {
            $arr['id'] = $v['id'];
            $arr['name'] = $v['name'];
            $arr['pid'] = $v['t_cate'];
            $arr['createTime'] = date('Y-m-d H:i:s', $v['time']);
            $arr['createBy'] = 1;
            $arr['updateTime'] = date('Y-m-d H:i:s', $v['time']);
            $arr['updateBy'] = 1;

            $insertId = Db::table('zb_category_management')->insert($arr);
            var_dump($insertId);
        }
    }
}