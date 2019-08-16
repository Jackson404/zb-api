<?php

namespace Util;

use think\cache\driver\Redis;

class RedisX
{
    public static function instance()
    {
        $options = [
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => 'zhengbu123',
            'select' => 0,
            'timeout' => 0,
            'expire' => 0,
            'persistent' => false,
            'prefix' => '',
        ];
        $redis = new Redis($options);
        return $redis;
    }

}