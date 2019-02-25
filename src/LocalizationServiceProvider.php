<?php

namespace AlexJoffroy\Localization;

use Illuminate\Support\ServiceProvider;
use AlexJoffroy\Localization\Localization;
use Illuminate\Foundation\Events\LocaleUpdated;
use AlexJoffroy\Localization\Strategies\Strategy;
use AlexJoffroy\Localization\Listeners\AppLocaleUpdated;

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

        $strategy = $this->app->config->get('localization.strategy');
        $this->app->bind(Strategy::class, $strategy);
        
        $this->app->singleton(Localization::class, function () {
            return new Localization($this->app);
        });

        $this->app->alias(Localization::class, 'localization');

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
