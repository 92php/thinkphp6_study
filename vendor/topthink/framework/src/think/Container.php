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

namespace think;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use think\exception\ClassNotFoundException;
use think\exception\FuncNotFoundException;
use think\helper\Str;

/**
 * 容器管理类 支持PSR-11
 */
class Container implements ContainerInterface, ArrayAccess, IteratorAggregate, Countable
{
    /**
     * 容器对象实例
     * @var Container|Closure
     */
    protected static $instance;

    /**
     * 容器中的对象实例
     * @var array
     */
    protected $instances = [];

    /**
     * 容器绑定标识
     * @var array
     */
    protected $bind = [];

    /**
     * 容器回调
     * @var array
     */
    protected $invokeCallback = [];

    /**
     * 获取当前容器的实例（单例）
     * @access public
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        if (static::$instance instanceof Closure) {
            return (static::$instance)();
        }

        return static::$instance;
    }

    /**
     * 设置当前容器的实例
     * @access public
     * @param object|Closure $instance
     * @return void
     */
    public static function setInstance($instance): void
    {
        static::$instance = $instance;
    }

    /**
     * 注册一个容器对象回调
     *
     * @param string|Closure $abstract
     * @param Closure|null   $callback
     * @return void
     */
    public function resolving($abstract, Closure $callback = null): void
    {
        if ($abstract instanceof Closure) {
            $this->invokeCallback['*'][] = $abstract;
            return;
        }

        $abstract = $this->getAlias($abstract);

        $this->invokeCallback[$abstract][] = $callback;
    }

    /**
     * 获取容器中的对象实例 不存在则创建
     * @access public
     * @param string     $abstract    类名或者标识
     * @param array|true $vars        变量
     * @param bool       $newInstance 是否每次创建新的实例
     * @return object
     */
    public static function pull(string $abstract, array $vars = [], bool $newInstance = false)
    {
        return static::getInstance()->make($abstract, $vars, $newInstance);
    }

    /**
     * 获取容器中的对象实例
     * @access public
     * @param string $abstract 类名或者标识
     * @return object
     */
    public function get($abstract)
    {
        if ($this->has($abstract)) {
            return $this->make($abstract);
        }

        throw new ClassNotFoundException('class not exists: ' . $abstract, $abstract);
    }

    /**
     * 绑定一个类、闭包、实例、接口实现到容器
     * @access public
     * @param string|array $abstract 类标识、接口
     * @param mixed        $concrete 要绑定的类、闭包或者实例
     * @return $this
     */
    public function bind($abstract, $concrete = null)
    {
        if (is_array($abstract)) {
            foreach ($abstract as $key => $val) {
                $this->bind($key, $val);
            }
        } elseif ($concrete instanceof Closure) {
            $this->bind[$abstract] = $concrete;
        } elseif (is_object($concrete)) {
            $this->instance($abstract, $concrete);
        } else {
            $abstract = $this->getAlias($abstract);
            if ($abstract != $concrete) {
                $this->bind[$abstract] = $concrete;
            }
        }

        return $this;
    }

    /**
     * 根据别名获取真实类名
     * @param  string $abstract
     * @return string
     */
    public function getAlias(string $abstract): string
    {
        if (isset($this->bind[$abstract])) {
            $bind = $this->bind[$abstract];

            if (is_string($bind)) {
                return $this->getAlias($bind);
            }
        }

        return $abstract;
    }

    /**
     * 绑定一个类实例到容器
     * @access public
     * @param string $abstract 类名或者标识
     * @param object $instance 类的实例
     * @return $this
     */
    public function instance(string $abstract, $instance)
    {
        $abstract = $this->getAlias($abstract);

        $this->instances[$abstract] = $instance;

        return $this;
    }

    /**
     * 判断容器中是否存在类及标识
     * @access public
     * @param string $abstract 类名或者标识
     * @return bool
     */
    public function bound(string $abstract): bool
    {
        return isset($this->bind[$abstract]) || isset($this->instances[$abstract]);
    }

    /**
     * 判断容器中是否存在类及标识
     * @access public
     * @param string $name 类名或者标识
     * @return bool
     */
    public function has($name): bool
    {
        return $this->bound($name);
    }

    /**
     * 判断容器中是否存在对象实例
     * @access public
     * @param string $abstract 类名或者标识
     * @return bool
     */
    public function exists(string $abstract): bool
    {
        $abstract = $this->getAlias($abstract);

        return isset($this->instances[$abstract]);
    }

    /**
     * 创建类的实例 已经存在则直接获取
     * @access public
     * @param string $abstract    类名或者标识
     * @param array  $vars        变量
     * @param bool   $newInstance 是否每次创建新的实例
     * @return mixed
     */
    public function make(string $abstract, array $vars = [], bool $newInstance = false)
    {
        $abstract = $this->getAlias($abstract);
        //如果已经存在实例，且不强制创建新的实例，直接返回已存在的实例
        if (isset($this->instances[$abstract]) && !$newInstance) {
            return $this->instances[$abstract];
        }

        //如果有绑定，且绑定的是闭包
        if (isset($this->bind[$abstract]) && $this->bind[$abstract] instanceof Closure) {
            //通过反射实执行方法
            $object = $this->invokeFunction($this->bind[$abstract], $vars);
        } else {
            //通过反射实例化需要的类，比如'think\Http'
            $object = $this->invokeClass($abstract, $vars);
        }

        if (!$newInstance) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * 删除容器中的对象实例
     * @access public
     * @param string $name 类名或者标识
     * @return void
     */
    public function delete($name)
    {
        $name = $this->getAlias($name);

        if (isset($this->instances[$name])) {
            unset($this->instances[$name]);
        }
    }

    /**
     * 执行函数或者闭包方法 支持参数调用
     * @access public
     * @param string|Closure $function 函数或者闭包
     * @param array          $vars     参数
     * @return mixed
     */
    public function invokeFunction($function, array $vars = [])
    {
        try {
            $reflect = new ReflectionFunction($function);
        } catch (ReflectionException $e) {
            throw new FuncNotFoundException("function not exists: {$function}()", $function, $e);
        }

        $args = $this->bindParams($reflect, $vars);

        return $function(...$args);
    }

    /**
     * 调用反射执行类的方法 支持参数绑定
     * @access public
     * @param mixed $method     方法
     * @param array $vars       参数
     * @param bool  $accessible 设置是否可访问
     * @return mixed
     */
    public function invokeMethod($method, array $vars = [], bool $accessible = false)
    {
        if (is_array($method)) {
            [$class, $method] = $method;

            $class = is_object($class) ? $class : $this->invokeClass($class);
        } else {
            // 静态方法
            [$class, $method] = explode('::', $method);
        }

        try {
            $reflect = new ReflectionMethod($class, $method);
        } catch (ReflectionException $e) {
            $class = is_object($class) ? get_class($class) : $class;
            throw new FuncNotFoundException('method not exists: ' . $class . '::' . $method . '()', "{$class}::{$method}", $e);
        }

        $args = $this->bindParams($reflect, $vars);

        if ($accessible) {
            $reflect->setAccessible($accessible);
        }

        return $reflect->invokeArgs(is_object($class) ? $class : null, $args);
    }

    /**
     * 调用反射执行类的方法 支持参数绑定
     * @access public
     * @param object $instance 对象实例
     * @param mixed  $reflect  反射类
     * @param array  $vars     参数
     * @return mixed
     */
    public function invokeReflectMethod($instance, $reflect, array $vars = [])
    {
        $args = $this->bindParams($reflect, $vars);

        return $reflect->invokeArgs($instance, $args);
    }

    /**
     * 调用反射执行callable 支持参数绑定
     * @access public
     * @param mixed $callable
     * @param array $vars       参数
     * @param bool  $accessible 设置是否可访问
     * @return mixed
     */
    public function invoke($callable, array $vars = [], bool $accessible = false)
    {
        if ($callable instanceof Closure) {
            return $this->invokeFunction($callable, $vars);
        } elseif (is_string($callable) && false === strpos($callable, '::')) {
            return $this->invokeFunction($callable, $vars);
        } else {
            return $this->invokeMethod($callable, $vars, $accessible);
        }
    }

    /**
     * 调用反射执行类的实例化 支持依赖注入
     * @access public
     * @param string $class 类名
     * @param array  $vars  参数
     * @return mixed
     */
    public function invokeClass(string $class, array $vars = [])
    {
        try {
            //通过反射实例化类
            $reflect = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException('class not exists: ' . $class, $class, $e);
        }

        if ($reflect->hasMethod('__make')) {
            //返回的$method包含'__make'的各种信息，如公有/私有
            $method = $reflect->getMethod('__make');
            //检查是否是公有方法且是静态方法
            if ($method->isPublic() && $method->isStatic()) {
                //绑定参数
                $args   = $this->bindParams($method, $vars);
                //调用该方法（__make），因为是静态的，所以第一个参数是null
                //因此，可得知，一个类中，如果有__make方法，在类实例化之前会首先被调用
                $object = $method->invokeArgs(null, $args);
                $this->invokeAfter($class, $object);
                return $object;
            }
        }

        //获取类的构造函数
        $constructor = $reflect->getConstructor();

        //有构造函数则绑定其参数
        $args = $constructor ? $this->bindParams($constructor, $vars) : [];

        //根据传入的参数，通过反射，实例化类
        $object = $reflect->newInstanceArgs($args);

        // 执行容器回调
        $this->invokeAfter($class, $object);

        return $object;
    }

    /**
     * 执行invokeClass回调
     * @access protected
     * @param string $class  对象类名
     * @param object $object 容器对象实例
     * @return void
     */
    protected function invokeAfter(string $class, $object): void
    {
        if (isset($this->invokeCallback['*'])) {
            foreach ($this->invokeCallback['*'] as $callback) {
                $callback($object, $this);
            }
        }

        if (isset($this->invokeCallback[$class])) {
            foreach ($this->invokeCallback[$class] as $callback) {
                $callback($object, $this);
            }
        }
    }

    /**
     * 绑定参数
     * @access protected
     * @param ReflectionFunctionAbstract $reflect 反射类
     * @param array                      $vars    参数
     * @return array
     */
    protected function bindParams(ReflectionFunctionAbstract $reflect, array $vars = []): array
    {
        //如果参数个数为0，直接返回
        if ($reflect->getNumberOfParameters() == 0) {
            return [];
        }

        // 判断数组类型 数字数组时按顺序绑定参数
        reset($vars);
        $type   = key($vars) === 0 ? 1 : 0;
        //通过反射获取函数的参数，比如，获取Http类构造函数的参数，为「App $app」
        $params = $reflect->getParameters();
        $args   = [];

        foreach ($params as $param) {
            $name           = $param->getName();
            $lowerName      = Str::snake($name);
            $reflectionType = $param->getType();

            if ($reflectionType && $reflectionType->isBuiltin() === false) {
                //将类型提示的参数实例化
                $args[] = $this->getObjectParam($reflectionType->getName(), $vars);
            } elseif (1 == $type && !empty($vars)) {
                $args[] = array_shift($vars);
            } elseif (0 == $type && array_key_exists($name, $vars)) {
                $args[] = $vars[$name];
            } elseif (0 == $type && array_key_exists($lowerName, $vars)) {
                $args[] = $vars[$lowerName];
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new InvalidArgumentException('method param miss:' . $name);
            }
        }

        return $args;
    }

    /**
     * 创建工厂对象实例
     * @param string $name      工厂类名
     * @param string $namespace 默认命名空间
     * @param array  $args
     * @return mixed
     * @deprecated
     * @access public
     */
    public static function factory(string $name, string $namespace = '', ...$args)
    {
        $class = false !== strpos($name, '\\') ? $name : $namespace . ucwords($name);

        return Container::getInstance()->invokeClass($class, $args);
    }

    /**
     * 获取对象类型的参数值
     * @access protected
     * @param string $className 类名
     * @param array  $vars      参数
     * @return mixed
     */
    protected function getObjectParam(string $className, array &$vars)
    {
        $array = $vars;
        $value = array_shift($array);

        // 如果传入的值已经是一个实例，直接返回
        if ($value instanceof $className) {
            $result = $value;
            array_shift($vars);
        } else {
            //实例化传入的类
            $result = $this->make($className);
        }

        return $result;
    }

    public function __set($name, $value)
    {
        $this->bind($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __isset($name): bool
    {
        return $this->exists($name);
    }

    public function __unset($name)
    {
        $this->delete($name);
    }

    public function offsetExists($key)
    {
        return $this->exists($key);
    }

    public function offsetGet($key)
    {
        return $this->make($key);
    }

    public function offsetSet($key, $value)
    {
        $this->bind($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->delete($key);
    }

    //Countable
    public function count()
    {
        return count($this->instances);
    }

    //IteratorAggregate
    public function getIterator()
    {
        return new ArrayIterator($this->instances);
    }
}
