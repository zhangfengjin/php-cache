<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/10
 * Time: 19:21
 */

namespace XYLibrary\Cache;


class Bootstrap
{
    //需要注册到类库中的服务

    protected $bootStraps = [
        '\XYLibrary\Cache\CacheManagerServiceProvider' => '\XYLibrary\Cache\CacheManagerServiceProvider'
    ];

    public function __construct()
    {
    }

    /**
     * 启动缓存
     */
    public function bootStrap()
    {
        //缓存调用方式-启动XYLibrary类库
        $bootStrap = new \XYLibrary\Bootstrap\Bootstrap();
        $bootStrap->bootstrap($this->bootStraps);
    }
}