<?php

namespace app\ans\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class Position extends Command
{
    protected function configure()
    {
        $this->setName('pos')->setDescription('command say pos');
    }

    protected function execute(Input $input, Output $output)
    {
        $file = ROOT_PATH.'public/data/position.json';
        $content = file_get_contents($file);
       // var_dump($content);
        $conArr = json_decode($content,true);
        //var_dump($conArr);

        foreach ($conArr as $con){
            var_dump($con);
        }
    }
}