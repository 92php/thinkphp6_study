<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
//定义根命名空间
namespace think;
//引入composer
require __DIR__ . '/../vendor/autoload.php';


// 当前应用目录
define('APP_PATH',dirname(__DIR__));
// WEB入口文件位置
define('PUBLIC_PATH','/public');
// 上传文件夹
define('UPLOAD','/uploads');
// 模板部署
define('TEMPLATE_PATH', 'template');


//通过Ioc容器将HTTP类实例出来
// 执行HTTP应用并响应
$http = (new App())->http; //(总的来说，整个过程大概是这样的：需要实例化 Http 类 ==> 提取构造函数发现其依赖 App 类 ==> 开始实例化 App 类（如果发现还有依赖，则一直提取下去，直到天荒地老）==> 将实例化好的依赖（App 类的实例）传入 Http 类来实例化 Http 类。)
//执行HTTP类中的run类方法 并返回一个response对象
$response = $http->run();
//执行response对象中的send类方法  该方法是处理并输出http状态码以及页面内容
$response->send();
//执行http对象中的end方法
$http->end($response);

//请求流程
/*访问入口文件index.php 同时 载入Composer的自动加载autoload文件
入口文件实例化系统应用基础类think\App
1、$http=new think\App->http;
2、$response=$http->run();
① $this->runWithRequest($request) #将请求信息对象传入
$this->initialize()
$this->app->initialize();
加载全局中间件（app/middleware.php）
解析多应用 $this->parseMultiApp()
设置开启事件机制
监听HttpRun ($this->app->event->trigger('HttpRun');)
app.with_route为true则闭包函数加载路由配置文件（$this->loadRoutes();）
$routePath = $this->getRoutePath();确定加载路径 如："D:\phpStudy\WWW\tp6\route\admin"
$files = glob($routePath . '.php'); //加载routePath所有的.php后缀的路由配置文件 $files = glob($routePath . '.php');
触发 监听 RouteLoaded事件
return $this->app->route->dispatch($request, $withRoute);//路由调度参数：$withRoute为包含加载路由文件的比好函数或者null； $withRoute();执行此闭包函数
return $this->check();//检测URL路由
...
② return $response->setCookie($this->app->cookie);
3、$response->send()
4、$http->end($response);
获取应用目录等相关路径信息
加载全局的服务提供provider.php文件
设置容器实例及应用对象实例，确保当前容器对象唯一
从容器中获取HTTP应用类think\Http
执行HTTP应用类的run方法启动一个HTTP应用
获取当前请求对象实例（默认为app\Request继承think\Request）保存到容器
执行think\App类的初始化方法initialize
加载环境变量文件.env和全局初始化文件
加载全局公共文件、系统助手函数、全局配置文件、全局事件定义和全局服务定义
判断应用模式（调试或者部署模式）
监听AppInit事件
注册异常处理
服务注册
启动注册的服务
加载全局中间件定义
如果是多应用模式则解析当前实际访问的应用名
自动多应用识别，检查域名绑定应用和应用映射及禁止访问列表
加载应用公共文件、应用配置文件、应用事件定义、应用中间件定义和应用服务提供定义
设置当前应用的命名空间
监听HttpRun事件
执行路由调度（Route类dispatch方法）
如果开启路由则检查路由缓存
加载路由定义
如果开启注解路由则检测注解路由
路由检测（中间流程很复杂 略）
路由调度对象think\route\Dispatch初始化
设置当前请求的控制器和操作名
注册路由中间件
绑定数据模型
设置路由额外参数
执行数据自动验证
执行路由调度子类的exec方法返回响应think\Response对象
获取当前请求的控制器对象实例
利用反射机制注册控制器中间件
执行控制器方法以及前后置中间件
执行当前响应对象的send方法输出
执行HTTP应用对象的end方法善后
监听HttpEnd事件
写入当前请求的日志信息
本地化当前请求的会话数据
至此，当前请求流程结束。*/
