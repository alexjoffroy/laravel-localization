<?php

use AlexJoffroy\Localization\Localization;
use AlexJoffroy\Localization\Strategies\Strategy;

if (!Route::hasMacro('locales')) {
    Route::macro('locales', function (Closure $closure) {
        // As this is a Router macro, `$this->container` reference `container` property on Router instance
        $l10n = $this->container->make(Localization::class);
        $strategy = $this->container->make(Strategy::class);
        
        $locales = $l10n->getSupportedLocalesKeys();
        $currentLocale = $l10n->getLocale();

        $locales->each(function ($locale) use ($closure, $l10n, $strategy) {
            $l10n->setLocale($locale);

            $strategy->registerRoute($locale, $closure);
        });
        
        $l10n->setLocale($currentLocale);
    });
}
