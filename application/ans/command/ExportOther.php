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

class ExportOther extends Command{
    protected function configure()
    {
     $this->setName('other');
    }

    protected function execute(Input $input, Output $output)
    {
        ini_set('memory_limit', '-1');
//C:\Users\Mloong\Pictures\简历\other\other\1\1
        $dir = "C:\Users\Mloong\Pictures\\简历\\other\\other\\1\\1\\";

        $dd = new DirX();
        $files = $dd->getDirExplorer($dir);

        $db = Db::connect($GLOBALS['dbConfigRds']);

        $reader = new Xlsx();
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

                if ($v[4] == null) {
                    $birth = 0;
                    $birthYear = 0;
                } else {
                    $birth = str_replace('/', '-', $v[4]);
                    $birthYear = date('Y',strtotime($birth));
                }
                if ($v[3] == null) {
                    $sex = -1;
                } else {
                    if ($v[3] == '男') {
                        $sex = 1;
                    } else {
                        $sex = 0;

                    }
                }

                $arr = [
                    'idCard' => 0,
                    'phone' => $v[6],
                    'name' => $v[0] == null ? '' : $v[0],
                    'sex' => $sex,
                    'birthYear' => $birthYear,
                    'birth' => $birth,
                    'educationName' => $v[13] == null ? '' : $v[13],
                    'mail' => $v[7] == null ? '' : $v[7],
                    'school'=>$v[11] == null ? '' : $v[11],
                    'profession'=>$v[12] == null ? '' : $v[12],
                    'workUnit'=>$v[10] == null ? '' : $v[10],
                    'workYear' => $v[5] == null ? '' : $v[5],
                    'exPosition' => $v[1] == null ? '' : $v[1],
                    'exSalary' => $v[14] == null ? '' : $v[14],
                    'exCity'=>$v[8] == null ? '' : $v[8],
                    'habitation' => $v[9] == null ? '' : $v[9],
                    'createTime' => $v[15] == null ? '' : str_replace('/', '-', $v[15]),
                    'deliveryTime' => $v[15] == null ? '' : str_replace('/', '-', $v[15]),
                    'from' => 'other',
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