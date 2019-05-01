<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/20/2019
 * Time: 9:10 AM
 */

namespace SaliBhdr\TyphoonCache\Exceptions;


class CacheKeyNotSetException extends \Exception
{


    public function __construct()
    {
        parent::__construct('Cache key is not set', 400);
    }


}