<?php

namespace AlexJoffroy\Localization\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AlexJoffroy\Localization\Localization
 */
class L10n extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'localization';
    }
}
