<?php


namespace app\logic;

use think\facade\Cache;
use app\facade\Redis;

class TokenBucketApi
{
    private $_burst = 10;//桶的容量
    private $_rate = 1; //令牌放入的速度
    private $_lastTime; //记录每次请求的时间
    private $_tokens; //当前令牌的数量
    private $_redis; //redis对象

    public function __construct()
    {
        $this->_redis = Redis::client();
        $this->_lastTime = time();
    }

    //要访问的接口
    public function getApi()
    {
        $res = $this->tokenBucket();
        $data = [
            'msg' => '获取成功',
            'code' => 200
        ];
        if (!$res) {
            $data['msg'] = '请稍后重试';
            $data['code'] = 400;
        }
        return $data;
    }

    public function tokenBucket()
    {
        $now = time();
        $redisKey = 'tokenBucket_api';
        $time = $this->_redis->get($redisKey);
        if (!empty($time)) {
            $this->_lastTime = time(); //获取上一次访问时间
        }
        $tokens = $this->_redis->get('tokens');
        if (!empty($tokens)) {
            $this->_tokens = $tokens; //获取当前令牌数量
        }
        //计算生成的令牌量
        //因为rate是固定的，所以可以认为“时间间隔 * rate”即为生成的令牌量
        $s = $now - $this->_lastTime; //当前时间减去上次访问时间，得到时间间隔
        //生成的令牌 =(当前时间-上次刷新时间)* 放入令牌的速率
        $addTokens = $s * $this->_rate;
        dump($addTokens);
        //当前令牌数= 之前的桶内令牌数量+放入的令牌数量(不能超过桶的总量)
        $this->_tokens = min($this->_burst, $this->_tokens + $addTokens);
        dump('令牌量:' . $this->_tokens);
        //桶里面还有令牌，请求正常处理
        $this->_lastTime = $now;
        $this->_redis->set($redisKey, $now);
        if ($this->_tokens < 1) {
            // 若不到1个令牌,则拒绝
            dump('拿不到令牌');
            return false;
        }
        //还有令牌，领取令牌
        $this->_tokens -= 1;
        //记录请求数
        $this->_redis->set('tokens', $this->_tokens);
        return true;

    }


}