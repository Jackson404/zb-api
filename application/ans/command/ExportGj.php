<?php

namespace app\ans\command;

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Exception;
use Util\DirX;

class ExportGj extends Command
{
    protected function configure()
    {
        $this->setName('exportGj');
    }

    protected function execute(Input $input, Output $output)
    {
        ini_set('memory_limit', '-1');
//C:\Users\Mloong\Pictures\简历\赶集\赶集\excel\1
        $dir = "C:\Users\Mloong\Pictures\\简历\\赶集\\赶集\\excel\\1\\";

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

                if ($v[2] == null) {
                    $birth = 0;
                    $birthYear = 0;
                } else {
                    $birth = (2016 - $v[2]) . '-00-00';
                    $birthYear = (2016 - $v[2]);
                }
                if ($v[1] == null) {
                    $sex = -1;
                } else {
                    if ($v[1] == '男') {
                        $sex = 1;
                    } else {
                        $sex = 0;

                    }
                }

                if ($v[10] == null || $v[10] == 0){
                    continue;
                }
                if (!isset($v[15])) {
                    $v[15] = '2016-07-23';
                }

                $arr = [
                    'idCard' => 0,
                    'phone' => $v[10],
                    'name' => $v[0] == null ? '' : $v[0],
                    'sex' => $sex,
                    'birthYear' => $birthYear,
                    'birth' => $birth,
                    'educationName' => $v[12] == null ? '' : $v[12],
                    'mail' => $v[11] == null ? '' : $v[11],
                    'workYear' => $v[9] == null ? '' : $v[9],
                    'exPosition' => $v[5] == null ? '' : $v[5],
                    'exSalary' => $v[8] == null ? '' : $v[8],
                    'exCity'=>$v[6] == null ? '' : $v[6],
                    'habitation' => $v[6] == null ? '' : $v[6],
                    'createTime' => $v[13] == null ? '' : str_replace('/', '-', $v[13]),
                    'deliveryTime' => $v[13] == null ? '' : str_replace('/', '-', $v[13]),
                    'from' => '赶集',
                ];

                foreach ($sqlData as $x => $xv) {
                    if ($sqlData[$x]['phone'] == $arr['phone']) {
//                        unset($sqlData[$x]);
                        continue;
                    }
                }
                array_push($sqlData, $arr);
                var_dump($arr);
                try {
                    $i = $db->table('zb_resume_new')->insert($arr);
                    var_dump($i . '---' . time());
                } catch (Exception $e) {
                    if ($e->getCode() == 10501) {
                        continue;
                    }
                    var_dump($e->getCode() . $e->getMessage());
                }
            }

//            sleep(1);
            $spreed->disconnectWorksheets();
            unset($spreed);

        }

    }
}