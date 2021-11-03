<?php

namespace app\controller;

use app\BaseController;
use think\captcha\facade\Captcha;

class Yzm extends BaseController
{
    public function index()
    {
        return Captcha::create();
    }
}