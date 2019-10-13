<?php

namespace SaliBhdr\TyphoonCache\Cache;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache as BaseCache;
use SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException;

class Cacher
{
    protected $cacheKey;

    protected $model;

    /**
     * @param $cacheable
     * @param null $expirationTime minutes
     * @return Cacher
     * @throws CacheKeyNotSetException
     */
    public function add($cacheable, $expirationTime = null)
    {
        $this->checkCacheKey();

        if (is_null($expirationTime))
            $expirationTime = $this->getExpTime($expirationTime);

        if ($expirationTime == -1) {
            BaseCache::forever($this->cacheKey, $cacheable);
        } else {
            BaseCache::add($this->cacheKey, $cacheable, $expirationTime);
        }

        return $this;
    }


    private function getExpTime($expirationTime = null)
    {
        if (is_null($expirationTime))
            $expirationTime = config('model-cache.default-cache-ttl');

        if (is_null($expirationTime)) {
            $expirationTime = 60;
        }

        return $expirationTime;
    }

    public function checkCacheKey()
    {
        if (is_null($this->cacheKey)) {
            throw new CacheKeyNotSetException();
        }
    }

    public function setCacheKeyByModel($id = null, $user_id = null)
    {
        $this->setCacheKey($this->getModel()->getCacheKey($id, $user_id));

        return $this;
    }

    public function setCacheKeyByMiddleware($route = null, $user_id = null)
    {
        $config = typhoonConfig();

        $key = null;

        if(is_null($route)){
            $route = getRequestUri(true);
        }

        if (!is_null($route) && isset($config['routes'][$route])) {
            $key = "";

            $routeConfig = $config['routes'][$route];

            if($routeConfig['prefix'])
                $key .= $routeConfig['prefix'].'_';

            $key .= '('.$route.')';

            if ($routeConfig['is_based_on_user']) {
                if (!is_null($user_id)) {
                    $key .= '_' . $user_id;
                } elseif ($this->getAuth()->check()) {
                    $key .= '_' . $this->getAuth()->id();
                }
            }
        }


        $this->setCacheKey($key);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     * @return Cacher
     */
    public function setModel($model): Cacher
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Returns a key from the cached configuration
     * @param null $key
     * @param null $default
     * @return mixed
     * @throws CacheKeyNotSetException
     */
    public function get($key = null, $default = null)
    {
        $this->checkCacheKey();

        $cache = BaseCache::get($this->cacheKey, $default);

        return $key ? (isset($cache[$key]) ? $cache[$key] : $cache) : $cache;
    }

    /**
     * Returns a key from the cached configuration
     * @param  string $key
     * @param null $default
     * @return mixed
     * @throws CacheKeyNotSetException
     */
    public function pull($key, $default = null)
    {
        $this->checkCacheKey();

        $cache = BaseCache::pull($this->cacheKey, $default);

        return $key ? (isset($cache[$key]) ? $cache[$key] : $cache) : $cache;
    }

    /**
     * Returns a key from the cached configuration
     * @param $cacheable
     * @param null $expirationTime minutes
     * @return mixed
     * @throws CacheKeyNotSetException
     */
    public function put($cacheable, $expirationTime = null)
    {
        $this->checkCacheKey();

        if (is_null($expirationTime))
            $expirationTime = $this->getExpTime($expirationTime);

        if ($expirationTime == -1) {
            BaseCache::forever($this->cacheKey, $cacheable);
        } else {
            BaseCache::put($this->cacheKey, $cacheable, $expirationTime);
        }

        return $this;
    }

    public function refresh($cacheable, $expirationTime = null)
    {

        $this->checkCacheKey();

        if (BaseCache::has($this->cacheKey))
            $this->forget($this->cacheKey);


        $this->add($cacheable, $expirationTime);
    }


    public function forget($cacheKey = null)
    {
        if (is_null($cacheKey)) {
            $cacheKey = $this->cacheKey;
        }

        BaseCache::forget($cacheKey);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * @param mixed $cacheKey
     * @return Cacher
     */
    public function setCacheKey($cacheKey): Cacher
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }


    protected function getAuth(){
        return auth();
    }
}
