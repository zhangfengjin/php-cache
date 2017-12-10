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

    protected $initConfig;
    protected $dirs = [
        'form' => __DIR__ . "/../../Config/",
        'to' => __DIR__ . "/../../../../../Config/"
    ];

    public function __construct($initConfig = true)
    {
        $this->initConfig = $initConfig;
    }

    /**
     * 启动缓存
     */
    public function bootStrap()
    {
        //缓存调用方式-启动XYLibrary类库
        $bootStrap = new \XYLibrary\Bootstrap\Bootstrap();
        if ($this->initConfig && file_exists($this->dirs["form"])) {
            //创建缓存基础配置文件
            copyDir($this->dirs["form"], $this->dirs["to"]);
        }
        $bootStrap->bootstrap($this->bootStraps);
    }
}