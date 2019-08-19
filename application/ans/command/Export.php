<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Exception;

class Export extends Command
{
    protected function configure()
    {
        $this->setName('one');
    }

    protected function execute(Input $input, Output $output)
    {
        ignore_user_abort(); // 后台运行
        set_time_limit(0); // 取消脚本运行时间的超时上限

        $db = Db::connect($GLOBALS['dbConfig2']);
        $rds = Db::connect($GLOBALS['dbConfigRds']);

        while (true) {
            $sql = "select resumeId,name,birth,sex,work,wage,qua,gra,spe,phone,mail,habitation,`from`,resume,createTime 
            from data_resume where isDelete = 0 limit 1";
            $res = $db->query($sql);
            $res = $res[0];
            if (empty($res)) {
                break;
            }
            $resumeId = $res['resumeId'];
            if ($res['birth'] != null || $res['birth'] != '') {
                $birthYear = date('Y', strtotime($res['birth']));
            } else {
                $birthYear = 0;
            }
            $createTime = date('Y-m-d', strtotime($res['createTime']));

            if ($res['sex'] == '男') {
                $sex = 1;
            } elseif ($res['sex'] == '女') {
                $sex = 0;
            } else {
                $sex = -1;
            }

            $data = [
                'idCard' => 0,
                'phone' => $res['phone'],
                'name' => $res['name'],
                'sex' => $sex,
                'birthYear' => $birthYear,
                'birth' => $res['birth'],
                'school' => $res['gra'],
                'educationName' => $res['qua'],
                'mail' => $res['mail'],
                'profession' => $res['spe'],
                'workYear' => $res['work'],
                'exSalary' => $res['wage'],
                'habitation' => $res['habitation'],
                'createTime' => $createTime,
                'deliveryTime' => $createTime,
                'from' => $res['from'],
                'resume'=>$res['resume']
            ];

            $rds->startTrans();

            try {
                $insertId = $rds->table('zb_resume_new')->insert($data);

                if ($insertId > 0) {
                    $updateRow = $db->table('data_resume')
                        ->where('resumeId', '=', $resumeId)->update(['isDelete' => 1]);
                    if ($updateRow > 0) {
                        var_dump($insertId . '--' . time());
                        $rds->commit();
                        $rds->close();
                        $db->close();
                    } else {
                        var_dump(-1);
                        $rds->rollback();
                        $rds->close();
                        $db->close();
                        break;
                    }
                } else {
                    var_dump(-2);
                    $rds->rollback();
                    $rds->close();
                    break;
                }

            } catch (Exception $e) {

                if ($e->getCode() == 10501) {
                    $upro = $db->table('data_resume')
                        ->where('resumeId', '=', $resumeId)
                        ->update(['isDelete' => 1]);
                    $rds->commit();
                    $rds->close();
                    $db->close();
                    var_dump($upro);
                }
                if ($e->getCode() != 10501) {
                    var_dump($e->getCode() . $e->getMessage() . $resumeId);
                    $rds->rollback();
                    $rds->close();
                    $db->close();
                    break;
                }
            }
        }
    }
}