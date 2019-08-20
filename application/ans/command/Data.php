<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class Data extends Command
{
    protected function configure()
    {
        $this->setName('hello')->setDescription('command say hello');
    }

    protected function execute(Input $input, Output $output)
    {
        $dirname = ROOT_PATH . '../../svn/51job/datajson/149';
        $filenameArr = bl_scandir($dirname);

        $db = Db::connect($GLOBALS['dbConfig2']);

        foreach ($filenameArr as $filename) {
            $jsonStr = file_get_contents($filename);
            $data = explode("\n", $jsonStr);
            foreach ($data as $k => $v) {
                $vArr = json_decode($v, true);
                if (count($vArr) < 16) {
                    unset($data[$k]);
                } else {
                    $data[$k] = $vArr;
                    $data[$k]['from'] = '51job';
                }

            }
            $result = $db->table('data_resume')->insertAll($data);
            var_dump($db->getLastInsID());
        }
    }
}