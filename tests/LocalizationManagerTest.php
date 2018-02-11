<?php

namespace AlexJoffroy\LaravelLocalization\Tests;

use AlexJoffroy\LaravelLocalization\LocalizationManager;

class LocalizationManagerTest extends TestCase
{
    /** @var \Illuminate\Contracts\Config\Repository */
    protected $config;

    /** @var \AlexJoffroy\LaravelLocalization\LocalizationManager */
    protected $localization;

    protected function setUp()
    {
        parent::setUp();

        $this->config = $this->app['config'];
        $this->localization = $this->app['localization'];
    }

    /** @test */
    public function it_can_resolve_the_localization_manager()
    {
        $this->assertTrue($this->localization instanceof LocalizationManager);
    }

    /** @test */
    public function it_can_get_the_current_locale()
    {
        $this->config->set('app.locale', 'en');
        
        $this->assertEquals($this->localization->getLocale(), 'en');
    }

    /** @test */
    public function it_can_set_the_current_locale()
    {
        $this->config->set('app.locale', 'en');

        $this->localization->setLocale('fr');

        $this->assertEquals($this->localization->getLocale(), 'fr');
    }
}
