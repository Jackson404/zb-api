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
//        $dirname = ROOT_PATH . 'public/ans';

        $dirname = ROOT_PATH . '../../svn/51job/datajson/149';
        $filenameArr = bl_scandir($dirname);

        $db = Db::connect($GLOBALS['dbConfig2']);

        foreach ($filenameArr as $filename) {

            //var_dump($filenameArr);
            $jsonStr = file_get_contents($filename);

            $data = explode("\n", $jsonStr);

            foreach ($data as $k => $v) {

                $vArr = json_decode($v,true);

//                var_dump($varr);

                if (count($vArr) < 16){
                    unset($data[$k]);
                }else{
                    $data[$k] = $vArr;
                    $data[$k]['from'] = '51job';
                }

               // var_dump($data);

            }

            //unset($data[count($data) - 1]);

            //$db->transaction(function () use ($db, $data) {
               // $db->table('data_resume_copy1')->insertAll($data);
                $result = $db->table('data_resume')->insertAll($data);
//                var_dump($result);
                var_dump($db->getLastInsID());

            //});


        }


    }
}