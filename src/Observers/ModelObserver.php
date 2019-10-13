<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/15/2019
 * Time: 3:20 PM
 */

namespace SaliBhdr\TyphoonCache\Observers;

use Illuminate\Database\Eloquent\Model;
use SaliBhdr\TyphoonCache\TyphoonCache;

class ModelObserver
{
    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function retrieved(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function creating(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function created(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function updating(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function updated(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function saved(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function deleting(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function deleted(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function forceDeleted(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function restoring(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Model $model
     * @return void
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    public function restored(Model $model)
    {
        $this->addModelEvent($model, __FUNCTION__);
    }

    /**
     * @param $model
     * @param $event
     * @throws \SaliBhdr\TyphoonCache\Exceptions\CacheKeyNotSetException
     */
    private function addModelEvent($model, $event)
    {

        if (!$model->isCacheActive())
            return;

        if ($model->isCacheDeleteEventActive($event)) {
            TyphoonCache::forgetModelCache($model);
        } elseif ($model->isCacheEventActive($event)) {
            TyphoonCache::cacheModel($model);
        }
    }
}