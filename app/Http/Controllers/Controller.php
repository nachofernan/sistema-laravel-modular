<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;

abstract class Controller implements HasMiddleware
{
    //
    public static function middleware(){}
}
