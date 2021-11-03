<?php

namespace app\controller;

use app\BaseController;
use think\helper\Str;

class Helper extends BaseController
{
    public function index()
    {
        $haystack = "abcdefg";
        $needles = 'aa';
        $r = Str::contains($haystack, $needles); // 检查字符串中是否包含某些字符串
        dump($r);

        $r = Str::endsWith("abcdef", 'm'); //检查字符串是否以某些字符串结尾
        dump($r);

        $r = Str::random($length = 16); //获取指定长度的随机字母数字组合的字符串
        dump($r);

        $s = "ajsmYrmm";
        $r = Str::lower($s);
        dump($r);
        $r = Str::upper($s);
        dump($r);
        $r = Str::length($s);
        dump($r);

        $r = Str::substr($s, 2, $length = 2);  //截取字符串
        dump($r);

    }
}