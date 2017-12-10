<?php
/**
 * 连接缓存实现接口
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/8
 * Time: 14:40
 */

namespace XYLibrary\Cache\Connectors;


interface ConnectInterface
{
    /**
     * 连接缓存实现接口
     * 返回具体的缓存实现对象实例 如RedisStore\FileStore等
     * @param $config
     * @return mixed
     */
    function connections($config);
}