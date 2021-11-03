<?php

namespace app\logic;

use think\facade\Cache;
use app\facade\Redis;

class SlideTimeWindow
{
    //要访问的接口
    public function getApi()
    {
        $res = $this->SlideTimeWindow();
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

    //滑动窗口个人认为其实就是多存了时间，每次请求进来后时间范围之外的数据将被动态删除。主要使用redis的zset结构来实现
    public function SlideTimeWindow()
    {
        $limitTime = 60;
        $maxCount = 10;
        $redisKey = 'slide_api';
        $nowTime = time();
        //使用管道提升性能
        $pipe = Redis::setMulti();
        //dump($pipe);
        //value 和 score 都使用时间戳，因为相同的元素会覆盖
        $sss = $pipe->setZadd($redisKey,$nowTime,$nowTime);
        //dump($sss);
        //移除时间窗口之前的行为记录，剩下的都是时间窗口内的
        $aaa = $pipe->setZremrangebyscore($redisKey,0,$nowTime-$limitTime);
        //dump($aaa);
        //获取窗口内的行为数量
        $bbb = $pipe->setZcard($redisKey);
        //dump($bbb);
        //多加一秒过期时间
        $ccc = $pipe->setPipeExpire($redisKey,$limitTime+1);
        //dump($ccc);
        //执行
        $replies = $pipe->setExec();
        dump($replies);
        return $replies[2] <= $maxCount;
    }
}