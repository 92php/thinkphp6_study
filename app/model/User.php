<?php

namespace app\model;
use think\Model;

class User extends Model
{

    public function getDataById($id){
        $user = $this->find($id);
        echo $user->getAttr('create_time');
        echo $user->getAttr('name');
    }

    //获取用户ID为1的用户信息
    public function getUserInfo()
    {
        return self::where('id', 1)->find();
    }


}