<?php

namespace AlexJoffroy\RouteLocalization\Providers;

use Closure;
use Illuminate\Support\ServiceProvider;
use AlexJoffroy\RouteLocalization\Manager;
use Illuminate\Foundation\Events\LocaleUpdated;
use AlexJoffroy\RouteLocalization\Listeners\AppLocaleUpdated;

class RouteLocalizationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/route-localization.php' => config_path('route-localization.php'),
        ], 'config');

        $this->app->events->listen(LocaleUpdated::class, AppLocaleUpdated::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/route-localization.php', 'route-localization');

        $this->app->singleton('route-localization', function () {
            return new Manager($this->app);
        });

        $this->registerHelpers();
        $this->registerMacros();
    }

    public function registerHelpers()
    {
        require_once __DIR__ . '/../Helpers/l10n.php';
        require_once __DIR__ . '/../Helpers/locale.php';
        require_once __DIR__ . '/../Helpers/locales.php';
    }

    public function registerMacros()
    {
        require_once __DIR__.'/../Macros/locales.php';
    }
}
