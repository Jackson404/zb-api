<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ChangeCreateTime extends Command
{
    protected function configure()
    {
        $this->setName('change');
    }

    protected function execute(Input $input, Output $output)
    {
        $db = Db::connect($GLOBALS['dbConfig2']);

        $count = 3500000;
//        $start = 800010;
//        $start = 2600025;
        $start = 2724510;

        $i = ceil($count / 30);
        for ($num = 1; $num <= 30; $num++) {
            $date = '2019-06-' . $num;
            $ii = $start + ($i * $num);
            $o = $ii - $i;
//            var_dump($date);
////            var_dump($o);
////            var_dump($ii);
            $res = $db->table('data_resume_tmp')->where('resumeId', '>', $o)
                ->where('resumeId', '<=', $ii)
                ->update(['createTime' => $date]);
            var_dump($res);
        }

    }
}