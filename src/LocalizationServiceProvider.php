<?php

namespace AlexJoffroy\Localization;

use AlexJoffroy\Localization\Listeners\AppLocaleUpdated;
use AlexJoffroy\Localization\Localization;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/localization.php' => config_path('localization.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/localization'),
        ], 'views');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'localization');

        $this->app->events->listen(LocaleUpdated::class, AppLocaleUpdated::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/localization.php', 'localization');

        $this->app->singleton('localization', function () {
            return new Localization($this->app);
        });

        $this->registerHelpers();
        $this->registerMacros();
    }

    public function registerHelpers()
    {
        require_once __DIR__ . '/Helpers/l10n.php';
        require_once __DIR__ . '/Helpers/locale.php';
        require_once __DIR__ . '/Helpers/locales.php';
    }

    public function registerMacros()
    {
        require_once __DIR__.'/Macros/locales.php';
    }
}
