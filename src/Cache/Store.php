<?php
/**
 * 缓存实现接口
 * 所有具体缓存实现必须实现此接口
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/6
 * Time: 10:35
 */

namespace XYLibrary\Cache;


interface Store
{
    /**
     * 获取key的值
     * @param string $key
     * @return string
     */
    function get($key);

    /**
     * 批量获取key的值
     * @param array $keys ['key1','key2','key3']
     * @return array
     */
    function mget(array $keys);

    /**
     * 添加元素
     * 存在则替换
     * 不存在则添加
     * @param string $key
     * @param string $value
     * @param $minutes 过期时间-分钟-支持小数到秒级
     * @return void
     */
    function put($key, $value, $minutes);

    /**
     * 批量添加元素
     * @param array $keys ['key1'=>'value1','key2'=>'value2']
     * @param $minutes 过期时间-分钟-支持小数到秒级
     * @return void
     */
    function mput(array $keys, $minutes);

    /**
     * 添加元素
     * 存在则不添加
     * 不存在则添加
     * @param string $key
     * @param string $value
     * @param $minutes 过期时间-分钟-支持小数到秒级
     * @return bool 是否添加成功
     */
    function add($key, $value, $minutes);

    /**
     * 添加元素
     * 永不过期
     * @param string $key
     * @param string $value
     * @return void
     */
    function forever($key, $value);

    /**
     * 增量
     * @param string $key
     * @param int $step 每次增长长度
     * @return int 增长后的长度
     */
    function increment($key, $step = 1);

    /**
     * 减量
     * @param string $key
     * @param int $step 每次减少长度
     * @return int 减少后的长度
     */
    function decrement($key, $step = 1);

    /**
     * 删除
     * @param string $key
     * @return bool 是否删除成功
     */
    function forget($key);
}