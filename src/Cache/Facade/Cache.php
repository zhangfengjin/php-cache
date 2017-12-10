<?php
/**
 * 缓存门面
 * 继承门面类
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/10
 * Time: 14:15
 */

namespace XYLibrary\Cache\Facade;


use XYLibrary\Facade\Facade;

class Cache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "cache";
    }
}