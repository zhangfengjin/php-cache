<?php
/**
 * 连接Redis具体实现
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/8
 * Time: 14:41
 */

namespace XYLibrary\Cache\Connectors;


use XYLibrary\Cache\RedisStore;
use XYLibrary\Contracts\Redis\Factory as Redis;

class RedisConnector implements ConnectInterface
{
    protected $redisManager;

    public function __construct(Redis $redisManager)
    {
        $this->redisManager = $redisManager;
    }

    /**
     * 获取redis缓存实现
     * @param $config
     * @return RedisStore
     */
    function connections($config)
    {
        return new RedisStore($this->redisManager, $config["prefix"], $config['connection']);
    }
}