<?php

namespace AlexJoffroy\RouteLocalization\Providers;

use Closure;
use Illuminate\Support\ServiceProvider;
use AlexJoffroy\RouteLocalization\Manager;

class RouteLocalizationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/localization.php' => config_path('localization.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/localization.php', 'localization');

        $this->app->singleton('localization', function () {
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
