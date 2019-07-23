<?php

namespace app\ans\command;

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use Util\DirX;

class ExportExcelToMysql extends Command
{
    protected function configure()
    {
        $this->setName('export');
    }

    protected function execute(Input $input, Output $output)
    {
        ini_set('memory_limit', '-1');

        $dir = "C:\Users\Mloong\Pictures\\简历\\excel\\excel\\3\\";

        $dd = new DirX();
        $files = $dd->getDirExplorer($dir);

        $db = Db::connect($GLOBALS['dbConfig2']);

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
//                $arr = [
//                    'name' => $v[0] ==null ? '' : $v[0],
//                    'position' => $v[2]==null ? '' : $v[2],
//                    'resume' => $v[3]==null ? '' : $v[3],
//                    'sex' => $v[4]==null ? '' : $v[4],
//                    'birth' => $v[5] == null ? '' : str_replace('/', '-', $v[5]),
//                    'work' => $v[6]==null ? '' : $v[6],
//                    'phone' => $v[7]==null ? '' : $v[7],
//                    'mail' => $v[8]==null ? '' : $v[8],
//                    'habitation' => $v[9]==null ? '' : $v[9],
//                    'profe' => $v[11]==null ? '' : $v[11],
//                    'profession' => $v[12]==null ? '' : $v[12],
//                    'gra' => $v[13]==null ? '' : $v[13],
//                    'spe' => $v[14]==null ? '' : $v[14],
//                    'qua' => $v[15]==null ? '' : $v[15],
//                    'wage' => $v[16]==null ? '' : $v[16],
//                    'from' => '智联招聘',
//                    'createTime' => $v[17] == null ? '' : str_replace('/', '-', $v[17])
//                ];
//
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

                $arr = [
                    'name' => $v[0] == null ? '' : $v[0],
//                    'position' => $v[1] == null ? '' : $v[1],
                    'resume' => $v[1] == null ? '' : $v[1],
                    'sex' => $v[2] == null ? '' : $v[2],
                    'birth' => $v[3] == null ? '' : str_replace('/', '-', $v[3]),
                    'work' => $v[4] == null ? '' : $v[4],
                    'phone' => $v[5] == null ? '' : $v[5],
                    'mail' => $v[6] == null ? '' : $v[6],
                    'habitation' => $v[7] == null ? '' : $v[7],
                    'profe' => $v[9] == null ? '' : $v[9],
                    'profession' => $v[10] == null ? '' : $v[10],
                    'gra' => $v[11] == null ? '' : $v[11],
                    'spe' => $v[12] == null ? '' : $v[12],
                    'qua' => $v[13] == null ? '' : $v[13],
                    'wage' => $v[14] == null ? '' : $v[14],
                    'from' => '智联招聘',
                    'createTime' => $v[15] == null ? '2019-07-19' : $v[15]
                ];

//                $arr = [
//                    'name' => $v[0] == null ? '' : $v[0],
//                    'sex' => $v[3] == null ? '' : $v[3],
//                    'position' => $v[1] == null ? '' : $v[1],
//                    'resume' => $v[2] == null ? '' : $v[2],
//
//                    'birth' => $v[4] == null ? '' : $v[4],
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
                var_dump($arr);
                array_push($sqlData, $arr);
            }

//            sleep(1);
            $spreed->disconnectWorksheets();
            unset($spreed);

        }

        var_dump($db->table('resume_zl')->insertAll($sqlData));
    }
}