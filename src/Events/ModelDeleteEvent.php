<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/13/2019
 * Time: 1:38 PM
 */

namespace SaliBhdr\TyphoonCache\Events;


use Illuminate\Database\Eloquent\Model;

class ModelDeleteEvent extends Event
{
    public $model;

    /**
     * Create a new event instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}