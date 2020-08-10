<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-08-10
 * Time: 09:17
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Common extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'CommonService';
    }

}