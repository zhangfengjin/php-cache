<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/12/8
 * Time: 14:40
 */

namespace XYLibrary\Cache\Connectors;


interface ConnectorInterface
{
    function connections(array $config);
}