<?php

namespace app\ans\command;

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Exception;
use Util\DirX;

class Export51job extends Command
{
    protected function configure()
    {
        $this->setName('export51');
    }

    protected function execute(Input $input, Output $output)
    {
        ini_set('memory_limit', '-1');

        //$dir = "C:\Users\Mloong\Pictures\\简历\\51jobexcel\\51jobexcel\\2\\";
        $dir = "D:\workplace\\1";

        $dd = new DirX();
        $files = $dd->getDirExplorer($dir);

        $db = Db::connect($GLOBALS['dbConfigRds']);

        $reader = new Xls();
//        $reader = IOFactory::createReader('Xlsx');
        $sqlData = [];
        foreach ($files as $file) {
            $spreed = $reader->load($file); // 载入excel文件
            $sheet = $spreed->getSheet(0); // 读取第一個工作表
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
            $range = $highestColumn . $highestRow;
            $data = $sheet->rangeToArray('A2:' . $range);

            foreach ($data as $k => $v) {

                $age = mb_substr($v[3],0,2);
                $nowYear = date('Y',time());
                $birthYear = $nowYear-$age;
                $birth = $birthYear.'-00-00';

                $arr = [
                    'idCard' => 0,
                    'phone' => $v[10],
                    'name' => $v[1] == null ? '' : $v[1],
                    'sex' => $v[2] == null ? '' : $v[2],
                    'birthYear' => $birthYear,
                    'birth' => $birth,
//                    'school' => $v[13] == null ? '' : $v[13],
                    'educationName' => $v[4] == null ? '' : $v[4],
//                    'mail' => $v[16] == null ? '' : $v[16],
//                    'profession' => $v[14] == null ? '' : $v[14],
                    'workYear' => $v[5] == null ? '' : $v[5],
                    'exPosition' => $v[8] == null ? '' : $v[8],
                    'exSalary' => $v[7] == null ? '' : $v[7],
                    'habitation' => $v[6] == null ? '' : $v[6],
//                    'houseLocation' => $v[10] == null ? '' : $v[10],
//                    'workUnit' => $v[19] == null ? '' : $v[19],
//                    'createTime' => $v[6] == null ? '' : str_replace('/', '-', $v[6]),
//                    'deliveryTime' => $v[6] == null ? '' : str_replace('/', '-', $v[6]),
//                    'from' => '51job',
                ];

//                $arr = [
//                    'name' => $v[0] == null ? '' : $v[0],
//                    'position' => $v[1] == null ? '' : $v[1],
//                    'resume' => $v[2] == null ? '' : $v[2],
//                    'sex' => $v[3] == null ? '' : $v[3],
//                    'birth' => $v[4] == null ? '' : str_replace('/', '-', $v[4]),
//                    'work' => $v[5] == null ? '' : $v[5],
//                    'phone' => $v[6] == null ? '' : $v[6],
//                    'mail' => $v[7] == null ? '' : $v[7],
//                    'habitation' => $v[8] == null ? '' : $v[8],
//                    'profe' => $v[9] == null ? '' : $v[9],
//                    'profession' => $v[10] == null ? '' : $v[10],
//                    'gra' => $v[11] == null ? '' : $v[11],
//                    'spe' => $v[12] == null ? '' : $v[12],
//                    'qua' => $v[13] == null ? '' : $v[13],
//                    'wage' => $v[14] == null ? '' : $v[14],
//                    'from' => '智联招聘',
//                    'createTime' => $v[15] == null ? '2019-07-19' : $v[15]
//                ];

//                $arr = [
//                    'name' => $v[0] == null ? '' : $v[0],
////                    'position' => $v[1] == null ? '' : $v[1],
//                    'resume' => $v[1] == null ? '' : $v[1],
//                    'sex' => $v[2] == null ? '' : $v[2],
//                    'birth' => $v[3] == null ? '' : str_replace('/', '-', $v[3]),
//                    'work' => $v[4] == null ? '' : $v[4],
//                    'phone' => $v[5] == null ? '' : $v[5],
//                    'mail' => $v[6] == null ? '' : $v[6],
//                    'habitation' => $v[7] == null ? '' : $v[7],
//                    'profe' => $v[9] == null ? '' : $v[9],
//                    'profession' => $v[10] == null ? '' : $v[10],
//                    'gra' => $v[11] == null ? '' : $v[11],
//                    'spe' => $v[12] == null ? '' : $v[12],
//                    'qua' => $v[13] == null ? '' : $v[13],
//                    'wage' => $v[14] == null ? '' : $v[14],
//                    'from' => '智联招聘',
//                    'createTime' => $v[15] == null ? '2019-07-19' : $v[15]
//                ];


                foreach ($sqlData as $x => $xv) {
                    if ($sqlData[$x]['phone'] == $arr['phone']) {
//                        unset($sqlData[$x]);
                        continue;
                    }
                }
                array_push($sqlData, $arr);
//                var_dump($arr);
                try {
                    $i = $db->table('zb_resume_new')->insert($arr);
                    var_dump($i . '---' . time());
                } catch (Exception $e) {
                    if ($e->getCode() == 10501) {
                        continue;
                    }
                }
            }

//            sleep(1);
            $spreed->disconnectWorksheets();
            unset($spreed);

        }

//        try {
//            var_dump($db->table('zb_resume_new')->insertAll($sqlData));
//        } catch (Exception $e) {
//            var_dump($e->getCode().$e->getMessage());
//        }
    }
}