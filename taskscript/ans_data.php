<?php
include_once '../vendor/autoload.php';
include_once '../thinkphp/start.php';


$result = \think\Db::connect($GLOBALS['dbConfig2'])->table('data_resume')->select();

var_dump($result);