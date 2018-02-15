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

        $this->registerMacros();
    }

    public function registerMacros()
    {
        if (!$this->app['router']->hasMacro('locales')) {
            require_once __DIR__.'/../Macros/locales.php';
        }
    }
}
