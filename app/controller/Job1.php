<?php

namespace app\controller;


use think\queue\Job;
use think\facade\Db;

class Job1
{
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array $data 发布任务时自定义的数据
     */
    public function fire(Job $job, array $data)
    {
        // 有些任务在到达消费者时，可能已经不再需要执行了
        $isJobStillNeedToBeDone = $this->checkDatabaseToSeeIfJobNeedToBeDone($data);
        if (!$isJobStillNeedToBeDone) {
            $job->delete();
            return;
        }


        $isJobDone = $this->doHelloJob($data);
        if ($isJobDone){
            $job->delete();
            echo "删除任务" . $job->attempts() . '\n';
        }else{
            if ($job->attempts() > 3){
                $job->delete();
                echo "超时任务删除" . $job->attempts() . '\n';
            }
        }

    }

    /**
     * 有些消息在到达消费者时,可能已经不再需要执行了
     * @param array $data
     * @return bool
     */
    private function checkDatabaseToSeeIfJobNeedToBeDone(array $data) {
        return true;
    }

    /**
     * 根据消息中的数据进行实际的业务处理...
     * @param array $data
     * @return bool
     */
    private function doHelloJob(array $data)
    {
        echo '执行业务逻辑:' . $data['bizId'] . '\n';
        //Db::table('log')->insert(['param'=>json_encode($data),'create_time'=>time()]);
        return true;
    }
}