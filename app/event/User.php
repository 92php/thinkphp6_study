<?php
declare (strict_types = 1);

namespace app\event;

use app\model\User as UserModel;

//生成了一个User事件类
class User
{
    public $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    //给用户登录次数+1
    public function setLoginCount()
    {
        $userInfo = $this->user->getUserInfo();
        $userInfo->login_count += 1;
        return $userInfo;
    }


}
