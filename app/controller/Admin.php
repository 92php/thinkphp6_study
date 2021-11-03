<?php

namespace app\controller;

use app\BaseController;
use think\wenhainan\Auth;

class Admin extends BaseController
{
    public function _initialize()
    {
        $controller = request()->controller();
        $action = request()->action();
        $auth = new Auth();
        if(!$auth->check($controller . '-' . $action, session('uid'))){
            $this->error('你没有权限访问');
        }
    }

}