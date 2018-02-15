<?php

namespace AlexJoffroy\LaravelLocalization\Tests;

use AlexJoffroy\LaravelLocalization\LocalizationManager;

class LocalizationManagerTest extends TestCase
{
    /** @var \Illuminate\Contracts\Config\Repository */
    protected $config;

    /** @var \AlexJoffroy\LaravelLocalization\LocalizationManager */
    protected $localization;

    protected $locales = [
        'en' => ['native' => 'English'],
        'fr' => ['native' => 'Français'],
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->config = $this->app['config'];
        $this->config->set('app.locale', 'en');
        
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
        $this->assertEquals($this->localization->getLocale(), 'en');
    }

    /** @test */
    public function it_can_set_the_current_locale()
    {
        $this->assertEquals($this->localization->getLocale(), 'en');
        
        $this->localization->setLocale('fr');

        $this->assertEquals($this->localization->getLocale(), 'fr');
    }

    /** @test */
    public function it_can_check_if_a_locale_is_the_current_one()
    {
        $this->assertTrue($this->localization->isCurrentLocale('en'));
        $this->assertFalse($this->localization->isCurrentLocale('fr'));
    }
    
    /** @test */
    public function it_can_get_the_supported_locales()
    {
        $this->config->set('localization.supported_locales', $this->locales);

        $this->assertEquals(collect($this->locales), $this->localization->getSupportedLocales());
    }

    /** @test */
    public function it_can_get_the_supported_locales_keys()
    {
        $this->config->set('localization.supported_locales', $this->locales);

        $this->assertEquals(collect($this->locales)->keys(), $this->localization->getSupportedLocalesKeys());
    }

    /** @test */
    public function it_can_check_if_a_locale_is_supported()
    {
        $this->config->set('localization.supported_locales', $this->locales);
        
        $this->assertTrue($this->localization->isSupportedLocale('en'));
        $this->assertFalse($this->localization->isSupportedLocale('es'));
    }

    /** @test */
    public function it_can_get_the_default_locale()
    {
        $default = 'en';
        $this->config->set('localization.default_locale', $default);

        $this->assertEquals($default, $this->localization->getDefaultLocale());
    }

    /** @test */
    public function it_can_check_if_a_locale_is_the_default_one()
    {
        $default = 'en';
        $this->config->set('localization.default_locale', $default);

        $this->assertTrue($this->localization->isDefaultLocale('en'));
        $this->assertFalse($this->localization->isDefaultLocale('fr'));
    }

    /** @test */
    public function it_can_generate_a_localized_route()
    {
        config([
            'localization.supported_locales' => $this->locales,
            'localization.default_locale' => 'en'
        ]);
        $this->app['translator']->addLines(['routes.posts' => 'posts'], 'en');
        $this->app['translator']->addLines(['routes.posts' => 'articles'], 'fr');

        $router = $this->app['router'];
        $router->locales(function () use ($router) {
            $router->get(trans('routes.posts')."/{id}", [
                'as' => 'posts.show',
                'uses' => function ($id) {
                    return 'post ' . $id;
                }
            ]);
        });

        $this->assertEquals(url('/en/posts/123'), $this->localization->route('posts.show', ['id' => 123], true, 'en'));
        $this->assertEquals('/en/posts/123', $this->localization->route('posts.show', ['id' => 123], false, 'en'));
        $this->assertEquals(url('/fr/articles/123'), $this->localization->route('posts.show', ['id' => 123], true, 'fr'));
        $this->assertEquals('/fr/articles/123', $this->localization->route('posts.show', ['id' => 123], false, 'fr'));
    }

    /** @test */
    public function it_can_generate_a_localized_route_guessing_the_current_locale()
    {
        config([
            'localization.supported_locales' => $this->locales,
            'localization.default_locale' => 'en'
        ]);
        $this->app['translator']->addLines(['routes.posts' => 'posts'], 'en');
        $this->app['translator']->addLines(['routes.posts' => 'articles'], 'fr');

        $router = $this->app['router'];
        $router->locales(function () use ($router) {
            $router->get(trans('routes.posts') . "/{id}", [
                'as' => 'posts.show',
                'uses' => function ($id) {
                    return 'post ' . $id;
                }
            ]);
        });

        $this->localization->setLocale('en');
        $this->assertEquals(url('/en/posts/123'), $this->localization->route('posts.show', ['id' => 123], true));
        $this->assertEquals('/en/posts/123', $this->localization->route('posts.show', ['id' => 123], false));

        $this->localization->setLocale('fr');
        $this->assertEquals(url('/fr/articles/123'), $this->localization->route('posts.show', ['id' => 123], true));
        $this->assertEquals('/fr/articles/123', $this->localization->route('posts.show', ['id' => 123], false));
    }

    /** @test */
    public function it_can_generate_a_localized_route_from_a_not_supported_locale()
    {
        config([
            'localization.supported_locales' => $this->locales,
            'localization.default_locale' => 'en'
        ]);
        $this->app['translator']->addLines(['routes.posts' => 'posts'], 'en');
        $this->app['translator']->addLines(['routes.posts' => 'articles'], 'fr');

        $router = $this->app['router'];
        $router->locales(function () use ($router) {
            $router->get(trans('routes.posts') . "/{id}", [
                'as' => 'posts.show',
                'uses' => function ($id) {
                    return 'post ' . $id;
                }
            ]);
        });

        $this->localization->setLocale('en');
        $this->assertEquals(url('/en/posts/123'), $this->localization->route('posts.show', ['id' => 123], true, 'not-supported'));
        $this->assertEquals('/en/posts/123', $this->localization->route('posts.show', ['id' => 123], false, 'not-supported'));

        $this->localization->setLocale('fr');
        $this->assertEquals(url('/fr/articles/123'), $this->localization->route('posts.show', ['id' => 123], true, 'not-supported'));
        $this->assertEquals('/fr/articles/123', $this->localization->route('posts.show', ['id' => 123], false, 'not-supported'));
    }

    /** @test */
    public function it_can_generate_a_localized_route_from_a_localized_route_name()
    {
        config([
            'localization.supported_locales' => $this->locales,
            'localization.default_locale' => 'en'
        ]);
        $this->app['translator']->addLines(['routes.posts' => 'posts'], 'en');
        $this->app['translator']->addLines(['routes.posts' => 'articles'], 'fr');

        $router = $this->app['router'];
        $router->locales(function () use ($router) {
            $router->get(trans('routes.posts') . "/{id}", [
                'as' => 'posts.show',
                'uses' => function ($id) {
                    return 'post ' . $id;
                }
            ]);
        });

        $this->localization->setLocale('en');
        $this->assertEquals(url('/fr/articles/123'), $this->localization->route('en.posts.show', ['id' => 123], true, 'fr'));
        $this->assertEquals('/fr/articles/123', $this->localization->route('en.posts.show', ['id' => 123], false, 'fr'));
    }
}
