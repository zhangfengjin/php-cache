<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/6
 * Time: 10:49
 */

namespace XYLibrary\Cache;


interface RepositoryInterface
{
    /**
     * 是否存在
     * @param $key
     * @return bool
     */
    function has($key);

    /**
     * 获取元素的值
     * 支持批量
     * @param string|array $key 'key1'|['key1','key2']
     * @param mixed $default 默认值-支持Closure
     * @return mixed
     */
    function get($key, $default = null);

    /**
     * 获取元素
     * 获取元素后删除
     * @param string $key
     * @param $default 默认值-支持Closure
     * @return mixed
     */
    function pull($key, $default = null);

    /**
     * 添加元素
     * 支持批量
     * @param string|array $key 'key1'|['key1'=>'value1','key2'=>'value2']
     * @param $value
     * @param $minutes 过期时间-分钟-支持小数到秒级
     * @return void
     */
    function put($key, $value, $minutes);

    /**
     * 添加元素
     * 不存在则添加
     * 存在则不添加
     * @param $key
     * @param $value
     * @param $minutes 过期时间-分钟-支持小数到秒级
     * @return bool 是否添加成功
     */
    function add($key, $value, $minutes);

    /**
     * 添加元素
     * 永不过期
     * @param $key
     * @param $value
     * @return void
     */
    function forever($key, $value);

    /**
     * 添加元素
     * 存在则返回value
     * 不存在则添加并返回新添加的value
     * @param $key
     * @param $minutes 过期时间-分钟-支持小数到秒级
     * @param \Closure $closure
     * @return mixed
     */
    function remember($key, $minutes, \Closure $closure);

    /**
     * 添加元素
     * 存在则返回value
     * 不存在则永久添加并返回新添加的value
     * @param $key
     * @param \Closure $closure
     * @return mixed
     */
    function rememberForever($key, \Closure $closure);

    /**
     * 增量
     * @param $key
     * @param int $step
     * @return int 增量后的值
     */
    function increment($key, $step = 1);

    /**
     * 减量
     * @param $key
     * @param int $step
     * @return int 减量后的值
     */
    function decrement($key, $step = 1);

    /**
     * 删除
     * @param $key
     * @return bool 是否删除成功
     */
    function forget($key);
}