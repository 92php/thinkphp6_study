<?php

namespace app\controller;

use app\BaseController;
use think\facade\Queue;
use think\facade\Log;

class News extends BaseController
{
    public function read(){
        return 111;
    }

    public function update(){
        return 222;
    }

    public function testLog()
    {
        Log::record('测试日志信息');
    }

    public function index()
    {
        //php think queue:work --queue helloJobQueue
        // echo phpinfo();exit();
        // 1.当前任务由哪个类来负责处理
        // 当轮到该任务时，系统将生成该类的实例，并调用其fire方法
        $jobHandlerClassName = 'app\controller\Job1';

        // 2.当任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName = "helloJobQueue";

        // 3.当前任务所需业务数据，不能为resource类型，其他类型最终将转化为json形式的字符串
        $jobData = ['ts' => time(), 'bizId' => uniqid(), 'a' => 1];

        // 4.将该任务推送到消息列表，等待对应的消费者去执行
        // 入队列，later延迟执行，单位秒，push立即执行
        $isPushed = Queue::later(10, $jobHandlerClassName, $jobData, $jobQueueName);

        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if ($isPushed !== false) {
            echo '推送成功';
        } else {
            echo '推送失败';
        }
    }

}