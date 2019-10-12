<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/13/2019
 * Time: 1:38 PM
 */

namespace SaliBhdr\TyphoonCache\Listeners;

use SaliBhdr\TyphoonCache\Events\ModelCacheEvent;
use SaliBhdr\TyphoonCache\TyphoonCache;

class ModelCacheListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ModelCacheEvent $event
     * @return void
     */
    public function handle(ModelCacheEvent $event)
    {
        TyphoonCache::cacheModel($event->model);
    }

}