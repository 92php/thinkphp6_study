<?php

namespace app\controller;

use app\BaseController;
use app\facade\Redis;

class TestRedis extends BaseController
{
    public function index()
    {
        /*$a = 1;
        $b = 4;
        $result = Redis::lock('lock:demo', function () use ($a, $b) {
            return $a + $b;
        }, 60);

        if ($result['code'] == 0){
            dump('操作频繁，请稍后再试');
        }
        dump($result);*/

        /*$a = 44;
        $b = 55;
        $result = Redis::cache('cache:demo', function () use ($a, $b) {
            return $a + $b;
        }, 5);
        dump($result);*/

        //$a = 100;
        //$res = Redis::setCache('cache:demo',$a,30);
        //dump($res);
        //$res1 = Redis::getCache('cache:demo');
        //dump($res1);


        // 24小时到期
        //Redis::setExpire(86400)->hSet('expire:demo', 'hash-key', 'hash-value');
        // 2021-08-24 23:59:59到期
        //Redis::setExpireAt(strtotime('2021-08-24 23:59:59'))->hSet('expireAt:demo', 'hash-key', 'hash-value');

        // 普通调用，直接跟redis方法名
        //Redis::set('set:demo', 132456);
        //$r = Redis::get('set:demo');
        //dump($r);

        //Redis::client()->set('set:demo1', 333333);
        //$r = Redis::client()->get('set:demo1');
        //dump($r);


    }
}