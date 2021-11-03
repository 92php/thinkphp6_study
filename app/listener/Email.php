<?php
declare (strict_types = 1);

namespace app\listener;

//生成一个Email监听类
class Email
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        echo '在Email监听器输出 <br />';
    }
}
