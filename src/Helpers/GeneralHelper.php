<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/20/2019
 * Time: 11:51 AM
 */

namespace SaliBhdr\TyphoonCache\Helpers;


class GeneralHelper
{

    public static function getRouteTtl($route)
    {
        return typhoonRouteConfig($route, 'cache-ttl') ?? typhoonConfig('default-cache-ttl',60);

    }

    public static function isRouteCacheable($route)
    {
        $config = typhoonRouteConfig($route);

        if (!isset($config))
            return false;

        if (!($config['is_cache_active']))
            return false;

        return true;
    }

}