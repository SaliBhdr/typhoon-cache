<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/13/2019
 * Time: 1:13 PM
 */

namespace SaliBhdr\TyphoonCache;

use Illuminate\Database\Eloquent\Model;
use SaliBhdr\TyphoonCache\Events\ModelCacheEvent;
use SaliBhdr\TyphoonCache\Events\ModelDeleteEvent;

trait CacheableModel
{
    protected $modelCacheConfig;

    protected $isCachedData = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->bootCache();
    }


    /**
     * boot the cache trait credentials into model
     *
     * @return void
     */
    public function bootCache(): void
    {
        $config = typhConfig();

        if (isset($config['models'][get_class($this)]))
            $this->setCacheModelConfig($config['models'][get_class($this)]);

        if ($config['cache-method'] == TyphCache::dispatcherEventMethod)
            $this->setDispatchesEvents();
    }

    /**
     * sets related cache config for model
     *
     * @param $modelCacheConfig
     * @return CacheableModel
     */
    protected function setCacheModelConfig($modelCacheConfig)
    {
        $this->modelCacheConfig = $modelCacheConfig;

        return $this;
    }

    /**
     * set events for eloquent's built in $dispatchesEvents array
     *
     * @return CacheableModel
     */
    protected function setDispatchesEvents()
    {
        if (empty($this->dispatchesEvents)) {
            $cachedOns = $this->modelCacheConfig['cache-on'];

            foreach ($cachedOns as $event => $status) {
                if ($status) {
                    $this->dispatchesEvents[$event] = $this->getCacheEvent();
                }
            }

            $deleteOns = $this->modelCacheConfig['delete-on'];
            foreach ($deleteOns as $event => $status) {
                if ($status) {
                    $this->dispatchesEvents[$event] = $this->getCacheDeleteEvent();
                }
            }
        }

        return $this;
    }

    /**
     * event for cache event; events that lie under cache-on in config file
     * @return string
     */
    private function getCacheEvent()
    {
        return ModelCacheEvent::class;
    }

    /**
     * event for cache delete events; events that lie under delete-on in config file
     *
     * @return string
     */
    private function getCacheDeleteEvent()
    {
        return ModelDeleteEvent::class;
    }

    /**
     * check if cache is active for model
     *
     * @param null $config
     * @return bool
     */
    public function isCacheActive($config = null)
    {
        if(!$this->shouldBeCasheabel()){
            return false;
        }

        if (is_null($config)) {
            $config = typhConfig();
        }

        if (is_null($this->modelCacheConfig)) {
            $this->setCacheModelConfig($config['models'][get_class($this)]);
        }

        if ($config['is_cache_active'] && $this->modelCacheConfig['is_cache_active'])
            return true;


        return false;
    }


    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeCasheabel() : bool
    {
        return true;
    }

    /**
     * if you want to customize what you want to cache just override this method
     *
     * @return Model | array
     */
    public function toCacheable()
    {
        return $this;
    }

    /**
     * get cache ttl for model
     *
     * @return mixed
     */
    public function getTtl()
    {
        if (is_null($this->modelCacheConfig['cache-ttl'])) {
            return config('model-cache.default-cache-ttl');
        }

        return $this->modelCacheConfig['cache-ttl'];
    }

    /**
     * returns related cache key for model
     * if its null in config file it sets the cache_key for model
     *
     * @param null $record_id
     * @param null $user_id
     * @return string
     */
    public function getCacheKey($record_id = null, $user_id = null)
    {
        if (is_null($this->modelCacheConfig['cache_key']) || trim($this->modelCacheConfig['cache_key']) == "") {
            $key = strtolower($this->getTable());
        } else {
            $key = $this->modelCacheConfig['cache_key'];
        }

        if (!is_null($record_id)) {
            $key .= '_' . $record_id;
        } else {
            $key .= '_' . $this->id;
        }

        if ($this->modelCacheConfig['is_based_on_user']) {
            if (!is_null($user_id)) {
                $key .= '_' . $user_id;
            } elseif ($this->getAuth()->check()) {
                $key .= '_' . $this->getAuth()->id();
            }
        }

        return $key;
    }

    /**
     * @return mixed
     */
    public function isCachedData(): bool
    {
        return $this->isCachedData;
    }

    /**
     * @param bool $isCachedData
     * @return CacheableModel
     */
    public function setIsCachedData(bool $isCachedData)
    {
        $this->isCachedData = $isCachedData;

        return $this;
    }

    public function isCacheEventActive($event)
    {
        if (isset($this->modelCacheConfig['cache-on'][$event]) && $this->modelCacheConfig['cache-on'][$event]) {
            return true;
        }

        return false;
    }

    public function isCacheDeleteEventActive($event)
    {
        if (isset($this->modelCacheConfig['delete-on'][$event]) && $this->modelCacheConfig['delete-on'][$event]) {
            return true;
        }

        return false;
    }

    /**
     * Create a collection of models from plain arrays.
     *
     * @param  array $items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function hydrate(array $items)
    {
        $instance = $this->newModelInstance();

        // Todo:: find better approach before connection. this way connection has been made and only we manipulate fetched data. may be fetching event would be more suitable

        return $instance->newCollection(array_map(function ($item) use ($instance) {

            if ($instance->isCacheActive()) {
                $cachedData = TyphCache::retrieveModel($instance, $item->id);
            }

            if (isset($cachedData)) {
                return $cachedData;
            } else {
                return $instance->newFromBuilder($item);
            }
        }, $items));
    }

    /**
     * gets related cache config for model
     *
     * @return array
     */
    protected function getCacheModelConfig()
    {
        return $this->modelCacheConfig;
    }

    public function getAuth(){
        return auth();
    }
}