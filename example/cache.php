<?php
/**
 * 缓存Demo
 * User: zhangfengjin
 * Date: 2017/11/23
 */
require_once __DIR__ . "/../vendor/autoload.php";
$bootStrap = new \XYLibrary\Cache\Bootstrap(false);
$bootStrap->bootStrap();

\XYLibrary\Cache\Facade\Cache::put("username1", "zfj" . time() . rand(1, 5), 1);
\XYLibrary\Cache\Facade\Cache::put("username2", "zfj" . time() . rand(5, 10), 1);
\XYLibrary\Cache\Facade\Cache::put([
    'username1' => "zfj" . time() . rand(10, 20),
    'username2' => "zfj" . time() . rand(20, 30)
], [], 1);
echo \XYLibrary\Cache\Facade\Cache::get("username") . "\r\n";
echo \XYLibrary\Cache\Facade\Cache::get("username1") . "\r\n";
echo \XYLibrary\Cache\Facade\Cache::get("username1") . "\r\n";
var_dump(\XYLibrary\Cache\Facade\Cache::get(['username1', 'username2']));
echo \XYLibrary\Cache\Facade\Cache::add("username1", "123", 10);
echo \XYLibrary\Cache\Facade\Cache::add("username3", "123", 10);
echo "\r\nusername3:";
echo \XYLibrary\Cache\Facade\Cache::remember("username3", 10, function () {
    return "zhangfj3";
});
echo \XYLibrary\Cache\Facade\Cache::remember("username4", 10, function () {
    return "zhangfj4";
});
echo \XYLibrary\Cache\Facade\Cache::rememberForever("username5", function () {
    return "zhangfj5";
});
var_dump(\XYLibrary\Cache\Facade\Cache::forget("username5"));
\XYLibrary\Cache\Facade\Cache::forever("username10", "zhangfj");


echo "end\r\n";









