<?php namespace Ionutmilica\Themes\Facades;

use Illuminate\Support\Facades\Facade;

class Theme extends Facade {

    public static function getFacadeAccessor()
    {
        return 'themes';
    }
}