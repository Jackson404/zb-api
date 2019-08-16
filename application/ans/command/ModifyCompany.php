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

    protected function execute1(Input $input, Output $output)
    {
        $oldCompany = Db::connect($GLOBALS['db_config_t'])->name('company')->select();
        foreach ($oldCompany as $k => $v) {
            $oldCompanyId = $v['id'];

//            $arr['id'] = $v['c_id'];
            $arr['name'] = $v['c_name'] ?? '';
            $arr['address'] = $v['addr'] ?? '';
            $arr['phone'] = $v['link_phone'] ?? '';
            $arr['nature'] = '';
            $arr['profile'] = $v['descr'] ?? '';
            $arr['createTime'] = $v['create_date'];
            $arr['createBy'] = 1;
            $arr['updateTime'] = $v['update_date'];
            $arr['updateBy'] = 1;

            $companyId = Db::table('zb_company_management')->insertGetId($arr);

            $jobs = Db::connect($GLOBALS['db_config_t'])->name('job')
                ->where('company_id', 'eq', $oldCompanyId)
                ->select();

            $applyCount = count($jobs);
            Db::table('zb_company_management')->update(['positionCount' => $applyCount, 'id' => $companyId]);

            foreach ($jobs as $vv) {
                $arr1['positionCateId'] = 0;
                $arr1['name'] = $vv['j_name'];
                $arr1['companyId'] = $companyId;
                $arr1['minPay'] = $vv['salary_start'];
                $arr1['maxPay'] = $vv['salary_end'];
                $arr1['pay'] = $vv['salary_start'] . '-' . $vv['salary_end'];
                $arr1['minWorkExp'] = 0;
                $arr1['maxWorkExp'] = 0;
                $arr1['workExp'] = 0;
                if ($vv['req_edu'] == 'gaozhong') {
                    $a = '高中';
                } else if ($vv['req_edu'] == 'dazhuan') {
                    $a = '大专';
                } else if ($vv['req_edu'] == 'chuzhong') {
                    $a = '初中';
                } else if ($vv['req_edu'] == 'qita') {
                    $a = '其他';
                } else if ($vv['req_edu'] == 'benke') {
                    $a = '本科';
                } else if ($vv['req_edu'] == 'zhongzhuan') {
                    $a = '中专';
                }
                $arr1['education'] = $a;
                $arr1['age'] = '';
                $arr1['num'] = $vv['zp_num'];
                $arr1['labelIds'] = '';
                $arr1['isSoldierPriority'] = 1;
                $arr1['positionRequirement'] = $vv['content'];

                $arr1['createTime'] = $vv['create_date'];
                $arr1['createBy'] = 1;
                $arr1['updateTime'] = $vv['update_date'];
                $arr1['updateBy'] = 1;

                $insertId = Db::table('zb_position_management')->insertGetId($arr1);
                var_dump($insertId);
            }

        }

    }

    protected function execute(Input $input, Output $output)
    {

        $oldCompany = Db::connect($GLOBALS['db_config'])->name('company')->select();
        foreach ($oldCompany as $k => $v) {
//            $arr['id'] = $v['c_id'];
            $oldCompanyId = $v['c_id'];
            $arr['name'] = $v['name'];
            $arr['address'] = $v['address'];
            $arr['phone'] = $v['phone'];
            $arr['nature'] = $v['xz'];
            $arr['profile'] = $v['des'];
            $arr['createTime'] = date('Y-m-d H:i:s', $v['time']);
            $arr['createBy'] = 1;
            $arr['updateTime'] = date('Y-m-d H:i:s', $v['time']);
            $arr['updateBy'] = 1;

            $companyId = Db::table('zb_company_management')->insertGetId($arr);

            $oldTopPic = Db::connect($GLOBALS['db_config'])
                ->name('topics')
                ->where('t_gs', 'eq', $oldCompanyId)
                ->select();

            $positionCount = count($oldTopPic);
            Db::table('zb_company_management')->update(['positionCount' => $positionCount, 'id' => $companyId]);

            foreach ($oldTopPic as $kk => $vv) {
                $arr1['positionCateId'] = $vv['t_cate'];
                $arr1['name'] = $vv['t_title'];
                $arr1['companyId'] = $companyId;
                $arr1['minPay'] = $vv['s_mon'];
                $arr1['maxPay'] = $vv['e_mon'];
                $arr1['pay'] = $vv['s_mon'] . '-' . $vv['e_mon'];
                $arr1['minWorkExp'] = 0;
                $arr1['maxWorkExp'] = 0;
                $arr1['workExp'] = 0;
                $arr1['education'] = $vv['t_xl'];
                $arr1['age'] = $vv['t_nj'];
                $arr1['num'] = $vv['t_rs'];
                $arr1['labelIds'] = $vv['t_bq'];
                $arr1['isSoldierPriority'] = $vv['t_jr'];
                $arr1['address'] = $vv['t_dz'];
                $arr1['positionRequirement'] = $vv['t_yq'];

                $arr1['createTime'] = date('Y-m-d H:i:s', $vv['t_date']);
                $arr1['createBy'] = 1;
                $arr1['updateTime'] = date('Y-m-d H:i:s', $vv['t_date']);
                $arr1['updateBy'] = 1;

                $insertId = Db::table('zb_position_management')->insertGetId($arr1);
                var_dump($insertId);

            }
        }
    }
}