<?php
declare (strict_types = 1);

namespace app\subscribe;

//创建一个订阅类
class User
{

    public function onUserLogin(){
        echo 'subscribe输出的onUserLogin<br />';
    }
    public function onEmail(){
        echo 'subscribe输出的onEmail<br />';
    }
    public function subscribe(){
        echo 'subscribe输出的subscribe<br />';
    }

}
