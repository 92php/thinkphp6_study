<?php
namespace app\listener;

//定义了一个事件监听类User
class User
{
    public function handle(\app\event\User $event)
    {
        $userInfo = $event->user->getUserInfo();
        echo 'listen监听器输出：' . json_encode($userInfo, JSON_UNESCAPED_UNICODE) . '<br />';
        echo 'listen监听器输出：' . json_encode($event->setLoginCount(), JSON_UNESCAPED_UNICODE) . '<br />';
    }
}