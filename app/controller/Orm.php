<?php

namespace app\controller;

use app\BaseController;
use think\facade\Db;

class Orm extends BaseController
{
    public function index()
    {
        //使用容器方式
        //$r = app("db")->table('user')->where('id',1)->find();
        //dump($r);

        //使用门面模式的Db类
        //$r = Db::table('user')->where('id',1)->find();
        //dump($r);

        //$r = Db::name("user")->where('id',1)->find();
        //dump($r);

        //如果希望查询数据不存在的时候返回空数组，可以使用
        //$r = Db::table('user')->where('id', 1000)->find(); //null
        //$r = Db::table('user')->where('id', 1000)->findOrEmpty(); //[]
        //dump($r);

        //如果没有查找到数据，则会抛出一个 think\db\exception\DataNotFoundException 异常
        //$r = Db::table('user')->where('id', 1000)->findOrFail();
        //dump($r);

        /*$list = Db::table('user')->where('status', 1)->select();
        foreach ($list as $user) {
            echo $user['name']."<br/>";
        }*/

        /*$list = Db::table('user')->where('status', 1)->select()->toArray();
        foreach ($list as $user) {
            echo $user['name']."<br/>";
        }*/

        /*// 获取数据集
        $users = Db::name('user')->select();
        // 遍历数据集
        foreach($users as $user){
            echo $user['name']."<br/>";
            echo $user['id']."<br/>";
        }*/

        /*// 获取数据集
        $users = Db::name('user')->select();
        // 直接操作第一个元素
        $item  = $users[0];
        var_dump($item);
        // 获取数据集记录数
        $count = count($users);
        var_dump($count);
        // 遍历数据集
        foreach($users as $user){
            echo $user['name']."<br/>";
            echo $user['id']."<br/>";
        }*/


        // 返回某个字段的值
        //$r = Db::table('user')->where('id', 1)->value('name');
        //dump($r);

        // 返回数组
        //$r = Db::table('user')->where('status',1)->column('name');
        //dump($r);

        // 指定id字段的值作为索引
        //$r = Db::table('user')->where('status',1)->column('name', 'id');
        //dump($r);

        // 指定id字段的值作为索引 返回所有数据
        //$r = Db::table('user')->where('status',1)->column('*','id');
        //dump($r);

        /*//我们可以全部用户表数据进行分批处理，每次处理 100 个用户记录
        Db::table('user')->chunk(100, function($users) {
            foreach ($users as $user) {
                // 对100条用户数据进行处理操作
            }
        });*/

        /*//通过从闭包函数中返回false来中止对后续数据集的处理
        Db::table('user')->chunk(100, function($users) {
            foreach ($users as $user) {
                // 处理结果集...
                if($user->status == 0){
                    return false;
                }
            }
        });*/

        //在chunk方法之前调用其它的查询方法
        /*Db::table('user')
            ->where('score','>',80)
            ->chunk(100, function($users) {
                foreach ($users as $user) {
                    //
                }
            });*/

    }
}