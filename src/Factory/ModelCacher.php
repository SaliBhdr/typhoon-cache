<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/15/2019
 * Time: 3:56 PM
 */

namespace SaliBhdr\TyphoonCache\Factory;

use SaliBhdr\TyphoonCache\Cache\Cacher;
use Illuminate\Database\Eloquent\Model;

class ModelCacher extends Cachers
{

    /**
     * makes a cacher based on model
     *
     * @param Model $model
     * @param null $id
     * @param null $user_id
     * @return Cacher
     */
    public static function makeModelCacher(Model $model, $id = null, $user_id = null): Cacher
    {
        return static::getCacher()
            ->setModel($model)
            ->setCacheKeyByModel($id, $user_id);
    }

}