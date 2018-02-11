<?php

namespace AlexJoffroy\LaravelLocalization\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AlexJoffroy\LaravelLocalization\LocalizationManager
 */
class Localization extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'localization';
    }
}
