<?php

namespace AlexJoffroy\LaravelLocalization\Providers;

use Closure;
use Illuminate\Support\ServiceProvider;
use AlexJoffroy\LaravelLocalization\LocalizationManager;

class LocalizationServiceProvider extends ServiceProvider
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
            return new LocalizationManager($this->app);
        });

        $this->registerHelpers();
        $this->registerMacros();
    }

    public function registerHelpers()
    {
        require_once __DIR__ . '/../Helpers/l10n.php';
    }

    public function registerMacros()
    {
        if (!$this->app->router->hasMacro('locales')) {
            require_once __DIR__.'/../Macros/locales.php';
        }
    }
}
