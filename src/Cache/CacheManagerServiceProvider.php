<?php
/**
 * 注册缓存服务提供者
 * 实现服务提供者接口
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/10
 * Time: 14:19
 */

namespace XYLibrary\Cache;


use XYLibrary\Cache\Connectors\RedisConnector;
use XYLibrary\Contracts\ServiceProvider\Factory as ServiceProvider;
use XYLibrary\IoC\Container;

class CacheManagerServiceProvider implements ServiceProvider
{
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     *注册
     */
    public function register()
    {
        $this->registerCache();
    }

    /**
     * 注册缓存
     */
    protected function registerCache()
    {
        $this->app->bind("cache", function ($app) {
            return tap(new CacheManager($app), function ($manager) {
                $this->registerRedisCache($manager);
            });
        });
    }

    /**
     * 添加缓存实现-redis
     * @param $manager
     */
    protected function registerRedisCache($manager)
    {
        $manager->addConnector("redis", function () {
            return new RedisConnector($this->app["redis"]);
        });
    }
}