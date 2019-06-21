<?php

use think\cache\driver\Redis;

include_once __DIR__ . '/../vendor/autoload.php';

function doNews()
{
    while (true) {

        $redis = new Redis();
        $result = $redis->get('xinwen1');
        var_dump($result);
        $a = $result;
        if ($result === false) {
            var_dump($a);
            $redis->handler()->rpush('ns', $a);
            return false;
        }
    }
    return true;
}

doNews();