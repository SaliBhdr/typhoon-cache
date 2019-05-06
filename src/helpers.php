<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/20/2019
 * Time: 9:00 AM
 */


if (!function_exists('typhConfig')) {
    /**
     * Get the configuration.
     *
     * @param  string $path
     * @return mixed
     */
    function typhConfig($path = "",$default = null)
    {
        if ($path && is_string($path)) {
            $path = 'typhoon-cache.' . $path;
        } else {
            $path = 'typhoon-cache';
        }

        return config($path,$default);
    }
}

if (!function_exists('typhRouteConfig')) {
    /**
     * Get the configuration.
     *
     * @param string $path
     * @param string $route
     * @return mixed
     */
    function typhRouteConfig(string $route = null, $path = "")
    {

        if (is_null($route)) {
            $route = getRequestUri();
        }

        if ($path) {
            $path = 'routes.' . $route . '.' . $path;
        } else {
            $path = 'routes.' . $route;
        }

        return typhConfig($path);
    }
}

if (!function_exists('request')) {
    /**
     * request object.
     *
     * @param null $input
     * @return \Illuminate\Http\Request | string
     */
    function request($input = null)
    {
        $request = Illuminate\Container\Container::getInstance()->make('request');

        if (!is_null($input) && is_string($input)) {
            return $request->input($input);
        }

        return $request;
    }
}
if (!function_exists('getRequestUri')) {

    /**
     * request uri without starting slash
     *
     * @param bool $withParams
     * @return null|string
     */
    function getRequestUri($withParams = false)
    {
        $request = request();

        $uri = $request->getRequestUri();

        if ($withParams)
            $uri = current(explode('?', $uri, 2));

        $uri = ltrim($uri, '/');

        return $uri == "" ? null : $uri;
    }
}

