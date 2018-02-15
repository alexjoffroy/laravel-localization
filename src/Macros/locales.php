<?php

use Illuminate\Support\Facades\Route;
use AlexJoffroy\LaravelLocalization\Facades\L10n;

Route::macro('locales', function (Closure $closure) {
    $locales = L10n::getSupportedLocalesKeys();
    $currentLocale = L10n::getLocale();
    foreach ($locales as $locale) {
        L10n::setLocale($locale);
        $prefix = L10n::shouldHideLocaleInUrl($locale) ? '' : $locale;
        Route::as("$locale.")
            ->prefix($prefix)
            ->group($closure);
    }
    L10n::setLocale($currentLocale);
});
