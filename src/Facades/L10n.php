<?php

namespace AlexJoffroy\RouteLocalization\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AlexJoffroy\RouteLocalization\RouteLocalization
 */
class L10n extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'route-localization';
    }
}
