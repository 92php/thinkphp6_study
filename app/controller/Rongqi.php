<?php

namespace app\controller;

use app\BaseController;

/**
 * 容器与依赖注入原理:
 * ---------------------------------------------------------------
 * 任何URL访问,最终都会定位到一个控制器,由控制器中具体方法来完成
 * 一个控制器对应着一个类,如果对这些类进行统一管理呢?容器是一个好方案
 * 容器: 除了类管理工具,还可用于将类的实例(实例是啥  对象啊)做为参数,传递给类方法时,触发对象依赖注入进行管理
 * 依赖注入: 将对象类型的数据,以参数的方式传到操作方法中去的技术
 * URL访问: 通过GET可以获取到普通查询变量,如数值,字符串等,但对象无法获取
 * 而控制器或模型类中很多方法是需要依赖外部对象才可以工作,如何操作呢?
 * 依赖注入提供了完美的解决方案:将对象传入到类方法中
 * 使用依赖注入方式接收对象参数主要是控制器类和模型类
 * 类中的构造器和普通方法都可以使用依赖注入导入对象
 * 只要对参数类型进行类型约束,就会自动触发依赖注入行为产生
 * 依赖注入时完成二件事:
 *      1. 自动调用指定类的构造器(如何有)完成类的实例化;
 *      2. 将实例化的对象赋值给类名后指定的变量名称;
 */

class Rongqi extends BaseController
{
    //【url访问】从URL中接受字符串或数值参数,按参数名称来获取
    public function getName($name='Peter')
    {
        return 'Hello '.$name;
    }
    //如果想在当前方法中使用一个对象做为参数,可以使用类型结束来触发依赖注入,生成一个对象
    public function getMethod(\app\common\Temp $temp)
    {
        //依赖注入的等价代码:
        // $temp = new \app\common\Temp;
        //依赖注入时无法给构造器传参数,所以在类中单独创建一个方法来初始化属性
        $temp->setName('网络科技');
        return $temp->getName();
    }

    //绑定一个类到容器
    public function bindClass()
    {
        //将一个类放到容器中: 相当于注册到容器中
        //可以使用助手函数bind()简化类名
        bind('temp','\app\common\Temp');
        //将容器中的类按别名取出并实例化: 实例化同时可执行构造方法给类属性初始化
        // 可以使用助手函数app()简化类名
        $temp = app('temp',['name'=>'Peter_Zhu']);
        return $temp->getName();
    }


}