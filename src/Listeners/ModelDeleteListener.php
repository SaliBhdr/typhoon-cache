<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/13/2019
 * Time: 1:38 PM
 */

namespace SaliBhdr\TyphoonCache\Listeners;

use SaliBhdr\TyphoonCache\Events\ModelDeleteEvent;
use SaliBhdr\TyphoonCache\TyphoonCache;

class ModelDeleteListener
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
     * @param ModelDeleteEvent $event
     * @return void
     */
    public function handle(ModelDeleteEvent $event)
    {
        TyphoonCache::cacheModel($event->model);
    }
}