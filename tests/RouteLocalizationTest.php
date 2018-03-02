<?php

namespace AlexJoffroy\RouteLocalization\Tests;

use AlexJoffroy\RouteLocalization\RouteLocalization;

class RouteLocalizationTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        
        $router = $this->app['router'];
        $router->locales(function () use ($router) {
            $router->get(trans('routes.posts') . "/{id}", [
                'as' => 'posts.show',
                'uses' => function ($id) {
                    return 'post ' . $id;
                }
            ]);
        });
    }

    /** @test */
    public function it_can_resolve_the_localization_manager()
    {
        $this->assertTrue($this->localization instanceof RouteLocalization);
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
        $this->assertNotEquals(setlocale(LC_TIME, 0), 'fr_FR.UTF-8');
        
        $this->localization->setLocale('fr');

        $this->assertEquals($this->localization->getLocale(), 'fr');
        $this->assertEquals(setlocale(LC_TIME, 0), 'fr_FR.UTF-8');
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
        $this->assertEquals(collect($this->locales), $this->localization->getSupportedLocales());
    }

    /** @test */
    public function it_can_get_the_supported_locales_keys()
    {
        $this->assertEquals(collect($this->locales)->keys(), $this->localization->getSupportedLocalesKeys());
    }

    /** @test */
    public function it_can_check_if_a_locale_is_supported()
    {
        $this->assertTrue($this->localization->isSupportedLocale('en'));
        $this->assertFalse($this->localization->isSupportedLocale('es'));
    }

    /** @test */
    public function it_can_get_the_default_locale()
    {
        // Default locale set to 'en' in self::setUp
        $this->assertEquals('en', $this->localization->getDefaultLocale());
    }

    /** @test */
    public function it_can_check_if_a_locale_is_the_default_one()
    {
        // Default locale set to 'en' in self::setUp
        $this->assertTrue($this->localization->isDefaultLocale('en'));
        $this->assertFalse($this->localization->isDefaultLocale('fr'));
    }

    /** @test */
    public function it_can_generate_a_localized_route()
    {
        $this->assertEquals(url('/en/posts/123'), $this->localization->route('posts.show', ['id' => 123], true, 'en'));
        $this->assertEquals('/en/posts/123', $this->localization->route('posts.show', ['id' => 123], false, 'en'));
        $this->assertEquals(url('/fr/articles/123'), $this->localization->route('posts.show', ['id' => 123], true, 'fr'));
        $this->assertEquals('/fr/articles/123', $this->localization->route('posts.show', ['id' => 123], false, 'fr'));
    }

    /** @test */
    public function it_can_generate_a_localized_route_guessing_the_current_locale()
    {
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
        $this->localization->setLocale('en');
        $this->assertEquals(url('/fr/articles/123'), $this->localization->route('en.posts.show', ['id' => 123], true, 'fr'));
        $this->assertEquals('/fr/articles/123', $this->localization->route('en.posts.show', ['id' => 123], false, 'fr'));
    }

    /** @test */
    public function it_can_switch_locale_from_current_route()
    {
        $this->get('/en/posts/123?foo=bar');
        $this->localization->setLocale('en');
        $this->assertEquals(url('/fr/articles/123?foo=bar'), $this->localization->currentRoute('fr'));
        $this->assertEquals('/fr/articles/123?foo=bar', $this->localization->currentRoute('fr', false));

        $this->get('/fr/articles/123?foo=bar');
        $this->localization->setLocale('fr');
        $this->assertEquals(url('/en/posts/123?foo=bar'), $this->localization->currentRoute('en'));
        $this->assertEquals('/en/posts/123?foo=bar', $this->localization->currentRoute('en', false));
    }
}
