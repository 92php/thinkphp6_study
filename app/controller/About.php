<?php

namespace app\controller;

use app\BaseController;
use app\common\MyServiceDemo;

class About extends BaseController
{
    public function index(MyServiceDemo $demo)
    {
        //在这里测试服务
        //因为在服务提供类app\service\MyService的boot方法中设置 $myStaticVar = 'abc' ,所有这里输出'abc'
        echo $demo->showVar();
        die;
    }
}