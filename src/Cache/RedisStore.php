<?php
/**
 * Redis缓存存储实现
 * 实现Redis存储接口
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/8
 * Time: 14:44
 */

namespace XYLibrary\Cache;


use XYLibrary\Contracts\Redis\Factory as Redis;

class RedisStore implements Store
{
    protected $redisManager;

    protected $prefix;

    protected $connection;

    public function __construct(Redis $redisManager, $prefix = '', $connection = 'default')
    {
        $this->redisManager = $redisManager;
        $this->prefix = $this->getPrefix($prefix);
        $this->connection = $connection;
    }

    /**
     * 获取元素
     * @param string $key
     * @return int|mixed|null|string
     */
    public function get($key)
    {
        $value = $this->getConnection()->get($this->prefix . $key);
        return is_null($value) ? null : $this->unserialize($value);
    }

    /**
     * 获取元素
     * 批量
     * @param array $keys ['key1','key2']
     * @return array ['key1'=>'value1','key2'=>'value2']
     */
    public function mget(array $keys)
    {
        $result = [];
        $values = $this->getConnection()->mget(array_map(function ($key) {
            return $this->prefix . $key;
        }, $keys));
        foreach ($values as $idx => $value) {
            $result[$keys[$idx]] = is_null($value) ? null : $this->unserialize($value);
        }
        return $result;
    }

    /**
     * 添加元素
     * 存在则覆盖
     * 不存在则添加
     * @param string $key
     * @param string $value
     * @param 过期时间 $minutes
     */
    public function put($key, $value, $minutes)
    {
        $this->getConnection()->setex($this->prefix . $key, (int)max(1, $minutes * 60), $this->serialize($value));
    }

    /**
     * 添加元素
     * 批量
     * @param array $keys ['key1'=>'value1','key2'=>'value2']
     * @param 过期时间 $minutes
     */
    public function mput(array $keys, $minutes)
    {
        $this->getConnection()->multi();
        foreach ($keys as $key => $value) {
            $this->put($key, $value, $minutes);
        }
        $this->getConnection()->exec();
    }

    /**
     * 添加元素
     * 不存在添加
     * 存在则不添加
     * @param string $key
     * @param string $value
     * @param 过期时间 $minutes
     * @return bool 是否添加成功
     */
    public function add($key, $value, $minutes)
    {
        $lua = "return redis.call('exists',KEYS[1])<1 and redis.call('setex',KEYS[1],ARGV[2],ARGV[1])";
        return (bool)$this->getConnection()->eval(
            $lua, 1, $this->prefix . $key, $this->serialize($value), (int)max(1, $minutes * 60)
        );
    }

    /**
     * 添加元素
     * 永不过期
     * @param string $key
     * @param string $value
     */
    public function forever($key, $value)
    {
        $this->getConnection()->set($this->prefix . $key, $this->serialize($value));
    }

    /**
     * 增量
     * @param string $key
     * @param int $step
     * @return int
     */
    public function increment($key, $step = 1)
    {
        return $this->getConnection()->incrby($this->prefix . $key, $step);
    }

    /**
     * 减量
     * @param string $key
     * @param int $step
     * @return int
     */
    public function decrement($key, $step = 1)
    {
        return $this->getConnection()->decrby($this->prefix . $key, $step);
    }

    /**
     * 删除元素
     * @param string $key
     * @return bool
     */
    public function forget($key)
    {
        return (bool)$this->getConnection()->del($this->prefix . $key);
    }

    /**
     * 获取key的前缀
     * @param $prefix
     * @return string
     */
    protected function getPrefix($prefix)
    {
        return empty($prefix) ? "" : $prefix . ":";
    }

    /**
     * @return \Predis\Client
     */
    protected function getConnection()
    {
        return $this->redisManager->connections($this->connection);
    }


    /**
     * 序列化
     * @param $value
     * @return int|string
     */
    protected function serialize($value)
    {
        return is_numeric($value) ? $value : serialize($value);
    }

    /**
     * 反序列化
     * @param $value
     * @return int|mixed|string
     */
    protected function unserialize($value)
    {
        return is_numeric($value) ? $value : unserialize($value);
    }
}