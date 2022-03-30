<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Db;

class Hello extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('hello')
            ->setDescription('the hello command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('hello');
    }






    public function djc()
    {
        set_time_limit(0);
        $forkNums = 20; //开启的进程数
        if (!function_exists("pcntl_fork")) {
            die("pcntl extention is must !");
        }

        for($i=0;$i<$forkNums;$i++){
            $pid = pcntl_fork();    //创建子进程
            if ($pid == -1) {
                //错误处理：创建子进程失败时返回-1.
                die('could not fork');
            } else if ($pid) {
                //父进程会得到子进程号，所以这里是父进程执行的逻辑
                //如果不需要阻塞进程，而又想得到子进程的退出状态，则可以注释掉pcntl_wait($status)语句，或写成：
                pcntl_wait($status,WNOHANG); //等待子进程中断，防止子进程成为僵尸进程。
            } else {
                //这里写子进程执行的逻辑
                $list = $this->mysql($v['start'],$v['rows']);
                foreach($list as $key=>$value){
                    $terminals = $this->getterminalinfo($value); //这里调用第三方接口，该过程大概需要3s
                    // ...  这里再对获取到的卡号信息进行自己相关的业务处理
                }
                unset($list);
                exit(0);
            }

        }

        /*其中需要注意的几个坑：

        1、如果在ThinkPHP中使用多进程，切勿在子进程中连接数据库，会出现gateway timeout错误，导致子进程终止，执行失败。引起原因为ThinkPHP在操作数据库后，没有主动关闭连接，导致连接超时无法连接数据库，解决办法，自己写数据库连接代码，操作完之后，mysql_close($conn)关闭连接

        2、在子进程中的变量，使用完之后，务必记得unset()注销变量，否则造成内存溢出

        3、子进程执行完毕之后，需要exit(0)退出程序，否则子进程无法退出，造成僵尸进程，占用系统资源。*/
    }







    public function start()
    {
        //$this->geData(1);

        $this->mult();

        //$this->logs();

        //$this->debug();


    }


    public function mult()
    {
        // 3个子进程处理任务
        // 145000
        for ($i = 0; $i < 145; $i++) {
            $pid = pcntl_fork();

            if ($pid == -1) {
                die("could not fork");

            } elseif ($pid) {
                //$this->geData($pid);


            } else {// 子进程处理

                //echo intval($i)."\r\n";

                $page = $i;
                //echo $page."\r\n";
                //exit($i);
                $this->geData($page);
                exit($i);// 一定要注意退出子进程,否则pcntl_fork() 会被子进程再fork,带来处理上的影响。
            }
        }

        // 等待子进程执行结束
        while (pcntl_waitpid(0, $status) != -1) {
            $status = pcntl_wexitstatus($status);
            $child = "Child $status completed\r\n";
            echo $child;
            file_put_contents('./log.txt', $child, FILE_APPEND);
        }
    }

    public function logs()
    {
        $file = file_get_contents('./log.txt');
        $arr = explode("\r\n", $file);
        dump($arr);

        $ids = [];
        foreach ($arr as $line) {
            $reg = "/\d+/";
            if (preg_match($reg, $line, $matches)) {
                dump($matches);
                array_push($ids, $matches[0]);
            }
        }

        dump($ids);
        sort($ids);

        dump($ids);

    }

    public function debug()
    {

        $page = 18;

        $limit = 1000;
        $start = ($page) * $limit + 1;
        $end = ($page + 1) * $limit;

        $flag = Db::name('decompose_r')->where('target_source_id', 'between', [$start, $end])->delete();

        echo "删除状态:" . $flag . "\r\n";

        $this->geData($page);
    }

    public function geData($page)
    {


        $limit = 1000;
        $start = ($page) * $limit + 1;
        $end = ($page + 1) * $limit;


        //Db::startTrans();
        $data = Db::name('target_source')
            ->where('status', 0)
            ->limit(1000)
            ->where('id', 'between', [$start, $end])
            //->lock(true)
            ->select();


        //$ids = array_column($data,'id');
        //Db::name('target_source')->where('id','in',$ids)->update(['status'=>1]);
        //Db::commit();
        //$ids = null;
        //exit;

        foreach ($data as $one) {
            $this->one($one, $page);
        }

        //$data = null;


    }

    private function one($info, $page)
    {


    }

}
