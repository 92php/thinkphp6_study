<?php

namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Session;

class TestVar extends BaseController
{
    public function index()
    {
        //$r = Request::param('name');
        //dump($r);
        //$r = Request::param();//全部请求变量 返回数组
        //dump($r);
        //$r = Request::param(['name', 'email']); //多个变量
        //dump($r);
        //$a = Request::param('a','1'); //$a不存在使用默认值1
        //dump($a);
        //$a = Request::param('username','','strip_tags'); //参数过滤 去掉html标签 htmlspecialchars转换成实体入库 strtolower小写
        //dump($a);
        //$r = Request::header(); //请求头数组,支持单个 cookie
        //dump($r);
        //$name = input('name');
        //dump($name);
        //$r = Request::cookie('name'); //获取 $_COOKIE 变量
        //dump($r);
        //$r = Request::server(); //获取 $_SERVER 变量
        //dump($r);
        //$r = Request::env(); //返回env数组
        //dump($r);
        //$r = Request::file(); //获取 $_FILES 变量
        //dump($r);

        //$r = Request::baseUrl();
        //dump($r);
        //$r = Request::host(true);
        //dump($r);
        //$r = Request::url(1);
        //dump($r);

        //$r = Request::domain(1);
        //dump($r);

        //$r = Request::time();
        //dump($r);

        //$r = Request::controller();
        //dump($r);

        //$r = Request::action();
        //dump($r);

        //$r = Request::method(true);
        //dump($r);

        //$r = Request::has('id','get'); //检测变量id是否存在
        //dump($r);

        $r = Request::session();//获取 $_SESSION 变量
        dump($r);

        Session::set('name', 'thinkphp');
        $r = Session::get('name');
        dump($r);

    }
}