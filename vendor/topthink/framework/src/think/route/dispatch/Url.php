<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2021 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think\route\dispatch;

use think\exception\HttpException;
use think\helper\Str;
use think\Request;
use think\route\Rule;

/**
 * Url Dispatcher
 */
class Url extends Controller
{

    public function __construct(Request $request, Rule $rule, $dispatch)
    {
        //获取传入来的Request对象，存储到类成员变量内
        $this->request = $request;
        //获取到路由列表，也放到类成员变量内
        $this->rule    = $rule;
        // 解析默认的URL规则
        $dispatch = $this->parseUrl($dispatch);
        //调用父类构造函数
        parent::__construct($request, $rule, $dispatch, $this->param);
    }

    /**
     * 解析URL地址
     * @access protected
     * @param  string $url URL
     * @return array
     */
    protected function parseUrl(string $url): array
    {
        //获取到分隔符
        $depr = $this->rule->config('pathinfo_depr');
        //获取当前域名
        $bind = $this->rule->getRouter()->getDomainBind();

        //如果域名不为空，并且正则匹配的到
        if ($bind && preg_match('/^[a-z]/is', $bind)) {
            //切割url，换成配置项中的PATH_INFO分隔符
            $bind = str_replace('/', $depr, $bind);
            // 如果有域名绑定
            $url = $bind . ('.' != substr($bind, -1) ? $depr : '') . ltrim($url, $depr);
        }

        //调用rule类中的parseUrlPath方法，切割pathinfo参数
        //如果url中有参数 返回一个demo吧  ['Index','Demo']
        //第一个为控制器、第二个为方法
        $path = $this->rule->parseUrlPath($url);
        //如果切割的pathinfo为空，则直接返回一个[null,null] 这样的一个空数组
        if (empty($path)) {
            return [null, null];
        }

        //获取到第一个下标  控制器
        // 解析控制器
        $controller = !empty($path) ? array_shift($path) : null;

        //正则匹配，如果匹配不到。就弹出一个HttpException异常
        if ($controller && !preg_match('/^[A-Za-z0-9][\w|\.]*$/', $controller)) {
            throw new HttpException(404, 'controller not exists:' . $controller);
        }

        // 解析操作
        $action = !empty($path) ? array_shift($path) : null;
        $var    = [];

        // 解析额外参数
        //类似于  /index.php/Index/Users/Pascc
        //这样就会返回一个 三个下标的数组
        if ($path) {
            //这里将多余的下标，放到var变量内
            preg_replace_callback('/(\w+)\|([^\|]+)/', function ($match) use (&$var) {
                $var[$match[1]] = strip_tags($match[2]);
            }, implode('|', $path));
        }

        //获取到泛域名 再判断其中是否有*符号
        $panDomain = $this->request->panDomain();
        if ($panDomain && $key = array_search('*', $var)) {
            // 泛域名赋值
            $var[$key] = $panDomain;
        }

        // 设置当前请求的参数
        $this->param = $var;

        // 封装路由
        $route = [$controller, $action];

        //判断路由，是否存在 不存在则弹出未找到路由
        if ($this->hasDefinedRoute($route)) {
            throw new HttpException(404, 'invalid request:' . str_replace('|', $depr, $url));
        }

        return $route;
    }

    /**
     * 检查URL是否已经定义过路由
     * @access protected
     * @param  array $route 路由信息
     * @return bool
     */
    protected function hasDefinedRoute(array $route): bool
    {
        [$controller, $action] = $route;

        // 检查地址是否被定义过路由
        $name = strtolower(Str::studly($controller) . '/' . $action);

        $host   = $this->request->host(true);
        $method = $this->request->method();

        if ($this->rule->getRouter()->getName($name, $host, $method)) {
            return true;
        }

        return false;
    }

}
