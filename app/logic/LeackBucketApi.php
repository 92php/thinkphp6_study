<?php

namespace app\logic;

use think\facade\Cache;
use app\facade\Redis;

class LeackBucketApi
{
    private $_water; //漏斗的当前水量（也就是请求数）
    private $_burst = 30; //漏斗总量（超过将直接舍弃）
    private $_rate = 1; //漏斗出水速率（限流速度）
    private $_lastTime; //记录每次请求的时间（因为需要记录每次请求之间的出水量也就是请求数）
    private $_redis; //redis对象

    public function __construct()
    {
        $this->_redis = Redis::client();
        $this->_lastTime = time();
    }

    //要访问的接口
    public function getApi()
    {
        $res = $this->leackBucket();
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

    public function leackBucket()
    {
        $now = time();
        $redisKey = 'leackBucket_api';
        $time = $this->_redis->get($redisKey);
        if(!empty($time)){
            $this->_lastTime = $time; //获取上一次访问时间
        }
        $water = $this->_redis->get('water');
        if(!empty($water)){
            $this->_water = $water; //获取当前剩余量也就是请求数
        }
        //计算出水量
        //因为rate是固定的，所以可以认为“时间间隔 * rate”即为漏出的水量
        $s = $now - $this->_lastTime;//当前时间减去上次访问时间，得到时间间隔
        $outCount = $s * $this->_rate;//漏出的水量（请求数）
        //执行漏水，计算剩余水量，也就是当前请求
        $this->_water = ($this->_water - $outCount);
        if($this->_water<0){
            $this->_water = 0; //重置为0
        }
        dump($this->_water.'-----请求数');
        //请求数超出了突发请求限制
        if($this->_water > $this->_burst){
            dump('超出桶限制');
            return false;
        }
        $this->_lastTime = $now; //更新时间
        $water++; //更新带待请求数
        $this->_redis->set($redisKey,$now);
        //记录请求数
        $this->_redis->set('water',$water);
        return true;

        //不足之处在于:
        //面对突发流量时会有大量请求失败,但是我们可以使用预先准备好的资源返回。
    }

}