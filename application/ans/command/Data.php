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
        $dirname = ROOT_PATH.'public/ans';
        $filenameArr =   bl_scandir($dirname);

        foreach ($filenameArr as $filename){
            $jsonStr = file_get_contents($filename);

            $data = explode("\n", $jsonStr);

            foreach ($data as $k => $v) {
                $data[$k] = json_decode($v, true);
                $data[$k]['from'] = '51job';
            }

            unset($data[count($data) - 1]);
//        var_dump($data);

            $result = Db::connect($GLOBALS['dbConfig2'])
                ->table('data_resume')
                ->insertAll($data);

            var_dump($result);

        }



    }
}