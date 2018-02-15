<?php

use Illuminate\Support\Facades\Route;
use AlexJoffroy\LaravelLocalization\Facades\L10n;

Route::macro('locales', function (Closure $closure) {
    $locales = L10n::getSupportedLocalesKeys();
    $currentLocale = L10n::getLocale();
    foreach ($locales as $locale) {
        L10n::setLocale($locale);
        Route::as("$locale.")
            ->prefix($locale)
            ->group($closure);
    }
    L10n::setLocale($currentLocale);
});
