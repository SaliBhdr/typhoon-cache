<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/15/2019
 * Time: 3:56 PM
 */

namespace SaliBhdr\TyphoonCache\Factory;

class MiddlewareCacher extends Cachers
{

    /**
     * makes a cacher based on route
     *
     * @param null $route
     * @param null $user_id
     * @return \SaliBhdr\TyphoonCache\Cache\Cacher
     */
    public static function makeMiddlewareCacher($route = null, $user_id = null)
    {
        return static::getCacher()
            ->setCacheKeyByMiddleware($route, $user_id);
    }

}