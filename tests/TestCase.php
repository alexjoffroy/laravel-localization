<?php

namespace AlexJoffroy\RouteLocalization\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use AlexJoffroy\RouteLocalization\RouteLocalizationServiceProvider;

class TestCase extends OrchestraTestCase
{
    /** @var \AlexJoffroy\RouteLocalization\RouteLocalization */
    protected $localization;

    protected $locales = [
        'en' => [
            'native' => 'English',
            'regional_code' => 'en_GB',
            'charset' => 'UTF-8',
            'constants' => ['LC_TIME'],
        ],
        'fr' => [
            'native' => 'Français',
            'regional_code' => 'fr_FR',
            'charset' => 'UTF-8',
            'constants' => ['LC_TIME'],
        ],
    ];

    protected function getEnvironmentSetUp($app)
    {
        $this->localization = $app['route-localization'];
        $app->setLocale('en');

        $app['config']->set([
            'route-localization.supported_locales' => $this->locales,
            'route-localization.default_locale' => 'en'
        ]);
        
        $app['config']->set('view.paths', [__DIR__ . '/stubs/views']);
        
        $app['translator']->addLines(['routes.posts' => 'posts'], 'en');
        $app['translator']->addLines(['routes.posts' => 'articles'], 'fr');
    }

    protected function getPackageProviders($app): array
    {
        return [RouteLocalizationServiceProvider::class];
    }
}
