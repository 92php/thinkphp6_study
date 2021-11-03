<?php
declare (strict_types=1);


namespace app\service;

use think\facade\Cache;


/**
 * 缓存服务
 * Class RedisService
 * @package app\api\service\common
 */
class RedisService
{
    private $expire;
    private $expire_at;
    private $pipe;

    /**
     * 获取redis句柄
     * @return object|null
     */
    public function client(): ?object
    {
        return Cache::store('redis')->handler();
    }


    /**
     * 处理缓存key（添加前缀...）
     * @param string $key key
     * @return string
     */
    private function cacheKey(string $key): string
    {

        return Cache::getCacheKey($key);
    }


    /**
     * 缓存程序运行结果
     * @param          $key
     * @param callable $callback
     * @param int $expire
     * @return mixed
     */
    public function cache($key, callable $callback, int $expire = 3600)
    {
        $cache = $this->client()->get($key);
        if (!$cache || !unserialize($cache)) {
            $data = $callback();
            $this->client()->set($key, $cache = serialize($data), $expire);
        }

        return unserialize($cache);
    }

    public function setCache($key, $data, int $expire = 3600)
    {
        return $this->client()->set($key, $cache = serialize($data), $expire);
    }

    public function getCache($key)
    {
        $cache = $this->client()->get($key);
        return $cache ? unserialize($cache) : '';
    }


    /**
     * 程序运行锁
     * @param          $key
     * @param callable $callback
     * @param int $timeout
     * @return array
     */
    public function lock($key, callable $callback, int $timeout = 10): array
    {
        $lock = $this->client()->get($key);
        if ($lock) return ['code' => 0, 'data' => null];
        $this->client()->setex($key, $timeout, 1);
        $data = $callback();
        $this->client()->del($key);

        return ['code' => 1, 'data' => $data];
    }


    /**
     * 设置有效时间
     * @param $ttl
     * @return $this|false
     * @throws \Exception
     */
    public function setExpire($ttl)
    {
        if ($this->expire_at) throw new \Exception('setExpire and setExpireAt can not set both');
        $this->expire = $ttl;

        return $this;
    }


    /**
     * 设置到期时间
     * @param $timestamp
     * @return $this|false
     * @throws \Exception
     */
    public function setExpireAt($timestamp)
    {
        if ($this->expire > 0) throw new \Exception('setExpire and setExpireAt can not set both');
        $this->expire_at = $timestamp;

        return $this;
    }


    public function setIncr($key)
    {
        return $this->client()->incr($key);
    }

    public function setIncrExpire($key,$time)
    {
        return $this->client()->expire($key,$time);
    }

    public function setMulti()
    {
        $this->pipe = $this->client()->multi();
        return $this;
    }

    public function setZadd($key,$time)
    {
        return $this->pipe->zadd($key,$time,$time);
    }

    public function setZremrangebyscore($key,$time)
    {
        return $this->pipe->zremrangebyscore($key,'0',(string)$time);
    }

    public function setZcard($key)
    {
        return $this->pipe->zcard($key);
    }

    public function setPipeExpire($key,$time)
    {
        return $this->pipe->expire($key,$time);
    }

    public function setExec()
    {
        return $this->pipe->exec();
    }

    /**
     * 调用原生redis方法
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $cache_key = $this->cacheKey($arguments[0]);
        $result = $this->client()->{$name}(...$arguments);
        // 设置过期时间
        $this->expire && $this->client()->expire($cache_key, $this->expire);
        $this->expire_at && $this->client()->expireAt($cache_key, $this->expire_at);

        return $result;
    }

}