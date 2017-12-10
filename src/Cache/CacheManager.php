<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/8
 * Time: 14:43
 */

namespace XYLibrary\Cache;


class CacheManager
{
    protected $app;
    protected $connectors;//具体缓存实现回调数组
    protected $connections;//具体缓存实现实例数组


    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 添加缓存实现回调
     * @param $driver
     * @param \Closure $closure
     */
    public function addConnector($driver, \Closure $closure)
    {
        if (!isset($this->connectors[$driver])) {
            $this->connectors[$driver] = $closure;
        }
    }

    /**
     * 获取具体实现的统一封装类对象
     * @param null $driver
     * @return Repository
     */
    public function connections($driver = null)
    {
        $driver = $driver ? $driver : $this->getDefaultDriver();
        if (!isset($this->connections[$driver])) {
            $this->connections[$driver] = $this->resolve($driver);
        }
        return new Repository($this->connections[$driver]);
    }

    /**
     * 解析具体实现
     * @param $driver
     * @return mixed
     */
    protected function resolve($driver)
    {
        if (!isset($this->connectors[$driver])) {
            throw new \RuntimeException("no match driver($driver) register");
        }
        $config = $this->getConfig($driver);
        $config["prefix"] = $this->getPrefix();
        $instance = call_user_func($this->connectors[$driver]);
        return $instance->connections($config);
    }

    /**
     * 获取默认缓存
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return $this->app["config"]["cache"]["default"];
    }

    /**
     * 获取缓存具体实现的配置信息
     * @param $driver
     * @return mixed
     */
    protected function getConfig($driver)
    {
        return $this->app["config"]["cache"]["stores"][$driver];
    }

    /**
     * 获取缓存的前缀标识
     * @return mixed
     */
    protected function getPrefix()
    {
        return $this->app["config"]["cache"]["prefix"];
    }

    /**
     * 魔术方法
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->connections()->$method(...$arguments);
    }
}