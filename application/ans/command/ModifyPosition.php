<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ModifyPosition extends Command
{
    protected function configure()
    {
        $this->setName('position');
    }

    protected function execute(Input $input, Output $output)
    {
        $oldTopPic = Db::connect($GLOBALS['db_config'])->name('topics')->select();
        foreach ($oldTopPic as $k => $v) {
            $arr['positionCateId'] = $v['t_cate'];
            $arr['name'] = $v['t_title'];
            $arr['companyId'] = $v['t_gs'];
            $arr['minPay'] = $v['s_mon'];
            $arr['maxPay'] = $v['e_mon'];
            $arr['pay'] = $v['s_mon'] . '-' . $v['e_mon'];
            $arr['minWorkExp'] = 0;
            $arr['maxWorkExp'] = 0;
            $arr['workExp'] = 0;
            $arr['education'] = $v['t_xl'];
            $arr['age'] = $v['t_nj'];
            $arr['num'] = $v['t_rs'];
            $arr['labelIds'] = $v['t_bq'];
            if ($v['t_jr'] == 1) {
                $arr['isSoldierPriority'] = 0;
            }
            if ($v['t_jr'] == 2) {
                $arr['isSoldierPriority'] = 1;
            }
//            $arr['isSoldierPriority'] = $v['t_jr'];
            $arr['address'] = $v['t_dz'];
            $arr['positionRequirement'] = $v['t_yq'];

            $arr['createTime'] = date('Y-m-d H:i:s', $v['t_date']);
            $arr['createBy'] = 1;
            $arr['updateTime'] = date('Y-m-d H:i:s', $v['t_date']);
            $arr['updateBy'] = 1;

            $insertId = Db::table('zb_position_management')->insert($arr);
            var_dump($insertId);
        }
    }
}