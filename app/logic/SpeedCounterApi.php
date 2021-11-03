<?php

namespace app\logic;

use think\facade\Cache;
use app\facade\Redis;

/**
 * 计数器实现限流
 * Class SpeedCounterApi
 * @package app\logic
 */
class SpeedCounterApi
{
    /**
     * redis 计数器实现方式
     * 限制一分钟内最大只能请求10次
     */
    public function SpeedCounter()
    {
        $limitTime = 60;
        $maxCount = 10;
        $redisKey = 'api';
        // incr命令
        // 为键 key 储存的数字值加一
        // 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 incr 操作

        $count = Redis::setIncr($redisKey);
        dump($count);
        if($count == 1) {
            //设置 key 过期时间
            $a = Redis::setIncrExpire($redisKey, $limitTime);
            dump($a);
        }

        //1分钟内超过最大请求数
        if($count>$maxCount){
            return false;
        }
        return true;

        //这方法存在的问题就是最后1秒内涌入所有请求,然后计数器过期重置后第一秒内又涌入大量请求 这样服务器还是可能会被高频访问搞挂
    }

    //要访问的接口
    public function getApi()
    {
        $res = $this->SpeedCounter();
        $data = [
            'msg' => '获取成功',
            'code' => 200
        ];
        if(!$res){
            $data['msg'] = '请稍后重试';
            $data['code'] = 400;
        }
        return $data;
    }
}