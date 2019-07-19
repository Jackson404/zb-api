<?php

namespace app\ans\command;

use Cache\Adapter\Redis\RedisCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Settings;
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

        $dir = "C:\Users\Mloong\Pictures\\excel\\excel\\1\\";

        $dd = new DirX();
        $files = $dd->getDirExplorer($dir);

        $db = Db::connect($GLOBALS['dbConfig2']);

        $reader = new Xlsx();
//        $reader = IOFactory::createReader('Excel5');
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

                if (!array_key_exists(0, $v)) {
                    $v[0] = '';
                }

                if (!array_key_exists(1, $v)) {
                    $v[1] = '';
                }

                if (!array_key_exists(2, $v)) {
                    $v[2] = '';
                }
                if (!array_key_exists(3, $v)) {
                    $v[3] = '';
                }
//                if (!array_key_exists(4, $v)) {
//                    $v[4] = '';
//                }
                if (!array_key_exists(4, $v)) {
                    $v[4] = str_replace('/', '-', $v[4]);
                } else {
                    $v[4] = '';
                }
                if (!array_key_exists(5, $v)) {
                    $v[5] = str_replace('/', '-', $v[5]);
                } else {
                    $v[5] = '';
                }
                if (!array_key_exists(6, $v)) {
                    $v[6] = '';
                }
                if (!array_key_exists(7, $v)) {
                    $v[7] = '';
                }
                if (!array_key_exists(8, $v)) {
                    $v[8] = '';
                }
                if (!array_key_exists(9, $v)) {
                    $v[9] = '';
                }
                if (!array_key_exists(10, $v)) {
                    $v[10] = '';
                }
                if (!array_key_exists(11, $v)) {
                    $v[11] = '';
                }
                if (!array_key_exists(12, $v)) {
                    $v[12] = '';
                }
                if (!array_key_exists(13, $v)) {
                    $v[13] = '';
                }
                if (!array_key_exists(14, $v)) {
                    $v[14] = '';
                }
//                if (!array_key_exists(15, $v)) {
//                    $v[15] = '';
//                }
                if (array_key_exists(15, $v)) {
                    $v[15] = str_replace('/', '-', $v[15]);
                } else {
                    $v[15] = '2019-07-19';
                }
                if (!array_key_exists(16, $v)) {
                    $v[16] = '';
                }
                if (array_key_exists(17, $v)) {
                    $v[17] = str_replace('/', '-', $v[17]);
                } else {
                    $v[17] = '2019-07-19';
                }
//                $arr = [
//                    'name' => $v[0],
//                    'position' => $v[2],
//                    'resume' => $v[3],
//                    'sex' => $v[4],
//                    'birth' => $v[5],
//                    'work' => $v[6],
//                    'phone' => $v[7],
//                    'mail' => $v[8],
//                    'habitation' => $v[9],
//                    'profe' => $v[11],
//                    'profession' => $v[12],
//                    'gra' => $v[13],
//                    'spe' => $v[14],
//                    'qua' => $v[15],
//                    'wage' => $v[16],
//                    'from' => '智联招聘',
//                    'createTime' => $v[17]
//                ];


                $arr = [
                    'name' => $v[0] ?? '',
                    'position' => $v[1] ?? '',
                    'resume' => $v[2] ?? '',
                    'sex' => $v[3] ?? '',
                    'birth' => $v[4] ?? '',
                    'work' => $v[5] ?? '',
                    'phone' => $v[6] ?? '',
                    'mail' => $v[7] ?? '',
                    'habitation' => $v[8] ?? '',
                    'profe' => $v[9] ?? '',
                    'profession' => $v[10] ?? '',
                    'gra' => $v[11] ?? '',
                    'spe' => $v[12] ?? '',
                    'qua' => $v[13] ?? '',
                    'wage' => $v[14] ?? '',
                    'from' => '智联招聘',
                    'createTime' => $v[15]
                ];
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