<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/15/2019
 * Time: 3:20 PM
 */

namespace SaliBhdr\TyphoonCache\Observers;

use Illuminate\Database\Eloquent\Model;
use SaliBhdr\TyphoonCache\TyphCache;

class ModelObserver
{
    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     */
    public function retrieved(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     */
    public function creating(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     */
    public function created(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     */
    public function updating(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     */
    public function updated(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     */
    public function saved(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User created event.
     *
     * @param Model $model
     * @return void
     */
    public function deleting(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Model $model
     * @return void
     */
    public function deleted(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Model $model
     * @return void
     */
    public function forceDeleted(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Model $model
     * @return void
     */
    public function restoring(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param Model $model
     * @return void
     */
    public function restored(Model $model)
    {
        $this->addModelEvent($model,__FUNCTION__);
    }

    /**
     * @param $model
     * @param $event
     */
    private function addModelEvent($model, $event){

        if ($model->isCacheActive()) {

            if ($model->isCacheDeleteEventActive($event)) {
                TyphCache::forgetModelCache($model);
            } elseif ($model->isCacheEventActive($event)) {
                TyphCache::cacheModel($model);
            }
        }
    }
}