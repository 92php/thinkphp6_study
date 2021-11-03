<?php

namespace app\facade;

use app\service\RedisService;
use think\Facade;

/**
 * Class Redis
 * @package app\api\facade
 * @method static \Redis client()
 * @method static \Redis setExpire($ttl)
 * @method static \Redis setExpireAt($timestamp)
 * @method static mixed cache($key, callable $callback, int $expire = 3600)
 * @method static array lock($key, callable $callback, int $timeout = 10)
 */
class Redis extends Facade
{

    protected static function getFacadeClass()
    {
        return RedisService::class;
    }
}