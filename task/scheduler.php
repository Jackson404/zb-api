<?php


use app\api\model\CompanyManagementModel;
use GO\Scheduler;

require_once  __DIR__ . '/../vendor/autoload.php';

// Create a new scheduler
$scheduler = new Scheduler();


// ... configure the scheduled jobs (see below) ...

//$scheduler->php(__DIR__ .'/script.php');
//$scheduler->php(__DIR__ .'/script.php')->at('* * * * *');
//$scheduler->php(__DIR__ .'/script.php')->everyMinute();
//$scheduler->php('script.php')->everyMinute(5);
//$scheduler->php('script.php')->hourly();
//$scheduler->php('script.php')->hourly(53);
//$scheduler->php('script.php')->daily();
//$scheduler->php('script.php')->daily(22, 03);
//$scheduler->php('script.php')->daily('22:03');
//$scheduler->php('script.php')->saturday();
//$scheduler->php('script.php')->friday(18);
//$scheduler->php('script.php')->sunday(12, 30);
//$scheduler->php('script.php')->january();
//$scheduler->php('script.php')->december(25);
//$scheduler->php('script.php')->august(15, 20, 30);
//$scheduler->php('script.php')->date('2018-01-01 12:20');
//$scheduler->php('script.php')->date(new DateTime('2018-01-01'));
//$scheduler->php('script.php')->date(DateTime::createFromFormat('!d/m Y', '01/01 2018'));
$result = \think\Db::connect($GLOBALS['db_config'])->table('zb_company_management')->select();

var_dump($result);

$scheduler->call(function () {

    return '123';

})->output('my.php');



// Let the scheduler execute jobs which are due.
$scheduler->run(new DateTime('2019-06-20 17:50'));
