<?php

namespace app\base\model;


class User extends Base
{
    public static function onBeforeUpdate($user)
    {
        if ('thinkphp' == $user->name) {
            return false;
        }
    }
}