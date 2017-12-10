<?php
/**
 * 缓存仓库封装
 * 实现仓库接口
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/8
 * Time: 14:44
 */

namespace XYLibrary\Cache;


class Repository implements RepositoryInterface
{
    protected $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * 是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return is_null($this->store->get($key));
    }

    /**
     * 获取元素
     * 支持批量
     * @param array|string $key
     * @param mixed $default
     * @return array|mixed|string
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->store->mget(array_values($key));
        }
        $value = $this->store->get($key);
        if (is_null($value)) {
            $value = $this->value($default);
        }
        return $value;
    }

    /**
     * 获取元素
     * 同时删除
     * @param string $key
     * @param 默认值 $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return $this->tap($this->get($key, $default), function ($value) use ($key) {
            $this->forget($key);
        });
    }

    /**
     * 添加元素
     * 支持批量
     * @param array|string $key
     * @param $value
     * @param 过期时间 $minutes
     */
    public function put($key, $value, $minutes)
    {
        if (is_array($key)) {
            $this->store->mput($key, $minutes);
            return;
        }
        $this->store->put($key, $value, $minutes);
    }

    /**
     * 添加元素
     * 存在则不添加
     * @param $key
     * @param $value
     * @param 过期时间 $minutes
     * @return bool
     */
    public function add($key, $value, $minutes)
    {
        return $this->store->add($key, $value, $minutes);
    }

    /**
     * 添加元素
     * 永不过期
     * @param $key
     * @param $value
     */
    public function forever($key, $value)
    {
        $this->store->forever($key, $value);
    }

    /**
     * 添加元素
     * 存在则不添加-返回存在的value
     * 不存在则添加-返回闭包的value
     * @param $key
     * @param 过期时间 $minutes
     * @param \Closure $closure
     * @return array|mixed|string
     */
    public function remember($key, $minutes, \Closure $closure)
    {
        $value = $this->get($key);
        if (!is_null($value)) {
            return $value;
        }
        return $this->tap($closure(), function ($value) use ($key, $minutes) {
            $this->put($key, $value, $minutes);
        });
    }

    /**
     * 添加元素
     * 存在则不添加-返回存在的value
     * 不存在则永久性添加-返回闭包的value
     * @param $key
     * @param \Closure $closure
     * @return array|mixed|string
     */
    public function rememberForever($key, \Closure $closure)
    {
        $value = $this->get($key);
        if (!is_null($value)) {
            return $value;
        }
        return $this->tap($closure(), function ($value) use ($key) {
            $this->forever($key, $value);
        });
    }

    /**
     * 增量
     * @param $key
     * @param int $step
     * @return int
     */
    public function increment($key, $step = 1)
    {
        return $this->store->increment($key, $step);
    }

    /**
     * 减量
     * @param $key
     * @param int $step
     * @return int
     */
    public function decrement($key, $step = 1)
    {
        return $this->store->decrement($key, $step);
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function forget($key)
    {
        return $this->store->forget($key);
    }

    /**
     * 获取值
     * @param $default
     * @return mixed
     */
    protected function value($default)
    {
        return $default instanceof \Closure ? $default() : $default;
    }

    /**
     * 链式
     * @param $value
     * @param $callback
     * @return mixed
     */
    protected function tap($value, $callback)
    {
        $callback($value);
        return $value;
    }
}