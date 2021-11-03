<?php

namespace app\controller;

use app\BaseController;
use think\facade\Event;
use app\model\User;
use app\validate\User as UserV;
use think\exception\ValidateException;
use think\facade\Request;

class TestUser extends BaseController
{

    public function index()
    {
        //……在此之前一系列的登录操作
        $user = new User();
        $userInfo = $user->getUserInfo();
        //echo '控制器输出：' . json_encode($userInfo, JSON_UNESCAPED_UNICODE) . '<br />';
        //Event::listen('UserLogin', 'app\listener\User');
        //Event::trigger('UserLogin');

        //echo '控制器输出：' . json_encode($userInfo, JSON_UNESCAPED_UNICODE) . '<br />';
        //Event::listen('UserLogin', 'app\listener\User');
        //Event::listen('UserLogin', 'app\listener\Email');
        //Event::trigger('UserLogin');

        //Event::listen('UserLogin', 'app\listener\User');
        //Event::listen('Email', 'app\listener\Email');
        //Event::trigger('UserLogin');
        //Event::trigger('Email');

        echo '控制器输出：' . json_encode($userInfo, JSON_UNESCAPED_UNICODE) . '<br />';
        Event::subscribe(\app\subscribe\User::class);
        Event::trigger('UserLogin');


    }

    public function test()
    {
        $name = Request::param('name');
        $email = Request::param('email');
        try {
            validate(UserV::class)->check([
                'name'  => $name,
                'email' => $email,
            ]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            dump($e->getError());
        }

        dump($name);
        dump($email);
    }
}