<?php

namespace AlexJoffroy\LaravelLocalization\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use AlexJoffroy\LaravelLocalization\Providers\LocalizationServiceProvider;

class TestCase extends OrchestraTestCase
{
    /** @var \AlexJoffroy\LaravelLocalization\LocalizationManager */
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

    protected function setUp()
    {
        parent::setUp();

        $this->localization = $this->app['localization'];
        $this->app->setLocale('en');

        $this->app['config']->set([
            'localization.supported_locales' => $this->locales,
            'localization.default_locale' => 'en'
        ]);
        $this->app['translator']->addLines(['routes.posts' => 'posts'], 'en');
        $this->app['translator']->addLines(['routes.posts' => 'articles'], 'fr');
    }

    protected function getPackageProviders($app): array
    {
        return [LocalizationServiceProvider::class];
    }
}
