<?php

namespace AlexJoffroy\LaravelLocalization\Providers;

use Illuminate\Support\ServiceProvider;
use AlexJoffroy\LaravelLocalization\LocalizationManager;

class LocalizationServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('localization', function () {
            return new LocalizationManager($this->app);
        });
    }
}
