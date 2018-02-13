<?php

namespace AlexJoffroy\LaravelLocalization\Providers;

use Closure;
use Illuminate\Support\ServiceProvider;
use AlexJoffroy\LaravelLocalization\LocalizationManager;

class LocalizationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('localization', function () {
            return new LocalizationManager($this->app);
        });

        $this->registerRouteLocalesMacro();
    }

    public function registerRouteLocalesMacro()
    {
        $router = $this->app['router'];
        $l10n = $this->app['localization'];
        $router->macro('locales', function (Closure $closure) use ($router, $l10n) {
            $locales = $l10n->getSupportedLocalesKeys();
            $currentLocale = $l10n->getLocale();
            foreach ($locales as $locale) {
                $l10n->setLocale($locale);
                $router->group([
                    'as' => "$locale.",
                    'prefix' => $locale,
                ], function () use ($closure) {
                    $closure();
                });
            }
            $l10n->setLocale($currentLocale);
        });
    }
}
