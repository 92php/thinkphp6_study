<?php

namespace app\base\model;

use think\model;


abstract class Base extends Model
{

    static public function showReturnCode($code = '', $data = [], $msg = '')
    {
        return \app\base\controller\Base::showReturnCode($code, $data, $msg);
    }

    static public function showReturnCodeWithOutData($code = '', $msg = '')
    {
        return \app\base\controller\Base::showReturnCode($code, [], $msg);
    }



}