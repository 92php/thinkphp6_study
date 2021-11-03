<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;

class Views extends BaseController
{
    public function index()
    {
        // 模板变量赋值
        View::assign('name','ThinkPHP');
        View::assign('email','thinkphp@qq.com');
        // 或者批量赋值
        /*View::assign([
            'name'  => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);*/
        // 模板输出
        return View::fetch('index');
    }
}