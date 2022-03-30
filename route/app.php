<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

//本地域名:http://www.tp.com
//http://www.tp.com/think
Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

//http://www.tp.com/hello/index
Route::get('hello/:name', 'index/hello');


//基础测试
Route::rule("test","Test/index","get");
Route::rule("test1","Test/test","get");


//路由使用
Route::rule('new/:id','News/read');
Route::rule('new/:id', 'News/update', 'POST');
//日志使用
Route::rule('log', 'News/testLog');
//队列使用
Route::rule('queue', 'News/index');
//服务使用
Route::rule("service","about/index","get");
//容器使用
Route::rule("get_name","Rongqi/getName","get");
Route::rule("get_method","Rongqi/getMethod","get");
Route::rule("get_b","Rongqi/bindClass","get");
//事件的使用
Route::rule("user","TestUser/index","get");
//orm使用
Route::rule("orm","orm/index","get");
//测试中间件 http://www.tp.com/hello1
//http://www.tp.com/hello1?name=think
Route::rule('hello1','test/test2','GET')->middleware([\app\middleware\Test::class]);
//redis使用
Route::rule("redis","TestRedis/index","get");
//模板的使用
Route::rule("moban","Views/index","get");
//数据验证  http://www.tp.com/test6?name="22222222222222"&email="2222222"
Route::rule("test6","TestUser/test","get");
//缓存使用
Route::rule("test2","Test/test1","get");
//tp相关变量使用
Route::rule("var","TestVar/index","get");
//tp相关助手函数的使用
Route::rule("helper","Helper/index","get");



//创建jwt
Route::rule("jwt","jwt/createJwt","get");
//验证jwt
Route::rule("verifyjwt","jwt/verifyJwt","post");
//elasticsearch使用
Route::rule("es","elastic/index","get");
//二维码使用
Route::rule("qc","qc/index","get");
//分词插件
Route::rule("fenci","fenci/jieba","get");
//导出excel
Route::rule("daochu","Daochu/index","get");

Route::rule("daochu1","Daochu/insert","get");

Route::rule("daochu2","Daochu/test","get");

//采集插件的使用
Route::rule("caiji","Caiji/index","get");
//验证码
Route::rule("yzm","Yzm/index","get");
//发送邮件 http://www.tp.com/email
Route::rule("email","Test/test4","get");
//生成pdf
Route::rule("pdf","Pdf/index","get");




//Aes加密算法
Route::rule("test3","Test/test3","get");
//隆过滤器 http://www.tp.com/test5
Route::rule("test5","Test/test5","get");
//限流算法测试
Route::rule("test7","Test/test6","get");
Route::rule("test8","Test/test7","get");
Route::rule("test9","Test/test8","get");
Route::rule("test10","Test/test9","get");