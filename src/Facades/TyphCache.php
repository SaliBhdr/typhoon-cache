<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/16/2019
 * Time: 10:29 AM
 */

namespace SaliBhdr\TyphoonCache\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \SaliBhdr\TyphoonCache\Cache\Cacher cacheModel(\Illuminate\Database\Eloquent\Model $model, $refresh = true || false)
 * @method static \SaliBhdr\TyphoonCache\Cache\Cacher forgetModelCache(\Illuminate\Database\Eloquent\Model $model, $id = null, $user_id = null)
 * @method static \Illuminate\Database\Eloquent\Model retrieveModel(\Illuminate\Database\Eloquent\Model $model, $id, $user_id = null)
 *
 * @method static \SaliBhdr\TyphoonCache\Cache\Cacher cacheRoute($route, $cacheable, $user_id = null, $refresh = true)
 * @method static \SaliBhdr\TyphoonCache\Cache\Cacher forgetRouteCache($route, $user_id = null)
 * @method static \Illuminate\Http\Response retrieveRouteCache($route, $user_id = null)
 *
 * @see \SaliBhdr\TyphoonCache\Factory\ModelCacher
 */
class TyphCache extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TyphoonCache';
    }
}