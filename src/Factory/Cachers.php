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

abstract class Cachers
{

    /**
     * get raw instance of Cacher
     *
     * @return Cacher
     */
    public static function getCacher(): Cacher
    {
        return new Cacher();
    }

}