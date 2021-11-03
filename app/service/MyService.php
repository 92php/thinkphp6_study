<?php
declare (strict_types = 1);

namespace app\service;
use app\common\MyServiceDemo;

class MyService extends \think\Service
{
    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
    	//将绑定标识到对应类
        $this->app->bind('my_service',MyServiceDemo::class);
    }

    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        //将被服务类的一个静态变量设置成另外一个值
        MyServiceDemo::setVar('abc');
    }
}
