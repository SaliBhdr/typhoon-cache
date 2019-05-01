<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/20/2019
 * Time: 8:33 AM
 */

namespace SaliBhdr\TyphoonCache;


use Illuminate\Database\Eloquent\Model;
use SaliBhdr\TyphoonCache\Factory\MiddlewareCacher;
use SaliBhdr\TyphoonCache\Factory\ModelCacher;
use SaliBhdr\TyphoonCache\Helpers\GeneralHelper;

class TyphCache
{

    const dispatcherEventMethod = 'dispatcher';
    const observerMethod = 'observer';
    /**
     * cache data of model
     *
     * @param Model $model
     * @param bool $refresh
     * @return Cache\Cacher
     * @throws Exceptions\CacheKeyNotSetException
     */
    public static function cacheModel(Model $model, $refresh = true)
    {
        $cacher = ModelCacher::makeModelCacher($model);

        if ($refresh) {
            $cacher->refresh($model->toCacheable(), $model->getTtl());
        } else {
            $cacher->add($model->toCacheable(), $model->getTtl());
        }

        return $cacher;
    }


    /**
     * retrieve model based on $id and $user_id
     *
     * @param Model $model
     * @param $id
     * @param null $user_id
     * @return Model
     * @throws Exceptions\CacheKeyNotSetException
     */
    public static function retrieveModel(Model $model, $id, $user_id = null)
    {
        $cacher = ModelCacher::makeModelCacher($model, $id, $user_id);

        $record = $cacher->get($cacher->getCacheKey());

        if (isset($record)) {
            if (is_object($record) && $record instanceof Model) {
                $record->setIsCachedData(true);
            } elseif (is_array($record)) {
                $model->forceFill($record);
                $model->syncOriginal($record);
                $record = $model->setIsCachedData(true);
            }

            return $record;
        }

        return null;
    }


    public static function cacheRoute($route, $cacheable, $user_id = null, $refresh = true)
    {
        if (GeneralHelper::isRouteCacheable($route)) {

            $cacher = MiddlewareCacher::makeMiddlewareCacher($route, $user_id);

            if ($refresh) {
                $cacher->refresh($cacheable, GeneralHelper::getRouteTtl($route));
            } else {
                $cacher->add($cacheable, GeneralHelper::getRouteTtl($route));
            }
        } else {
            $cacher = ModelCacher::getCacher();
        }

        return $cacher;
    }

    /**
     * retrieve response based on route and $user_id
     *
     * @param $route
     * @param null $user_id
     * @return \Illuminate\Http\Response
     * @throws Exceptions\CacheKeyNotSetException
     */
    public static function retrieveRouteCache($route, $user_id = null)
    {
        return MiddlewareCacher::makeMiddlewareCacher($route, $user_id)->get();
    }

    /**
     * @param $route
     * @param null $user_id
     * @return Cache\Cacher
     */
    public static function forgetRouteCache($route, $user_id = null)
    {
        return MiddlewareCacher::makeMiddlewareCacher($route, $user_id)->forget();
    }

    /**
     *
     *
     * @param Model $model
     * @param null $id
     * @param null $user_id
     * @return Cache\Cacher
     */
    public static function forgetModelCache(Model $model, $id = null, $user_id = null)
    {
        return ModelCacher::makeModelCacher($model, $id, $user_id)->forget();
    }

}