<?php

namespace SaliBhdr\TyphoonCache\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use SaliBhdr\TyphoonCache\Helpers\GeneralHelper;
use SaliBhdr\TyphoonCache\TyphoonCache;

class CacheMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (GeneralHelper::isRouteCacheable(getRequestUri())) {

            $cachedResponse = TyphoonCache::retrieveRouteCache(getRequestUri(true), $this->auth->guard($guard)->id());

            if (isset($cachedResponse)) {
                return $cachedResponse;
            } else {
                TyphoonCache::cacheRoute(getRequestUri(true), $next($request), $this->auth->guard($guard)->id());
            }
        }

        return $next($request);
    }

}
