<?php

use Illuminate\Support\Facades\Route;

Route::macro('locales', function (Closure $closure) {
    $locales = l10n()->getSupportedLocalesKeys();
    $currentLocale = l10n()->getLocale();
    foreach ($locales as $locale) {
        l10n()->setLocale($locale);
        $prefix = l10n()->shouldHideLocaleInUrl($locale) ? '' : $locale;
        Route::as("$locale.")
            ->prefix($prefix)
            ->group($closure);
    }
    l10n()->setLocale($currentLocale);
});
