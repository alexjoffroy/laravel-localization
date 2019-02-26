<?php

namespace AlexJoffroy\Localization\Tests\Macros;

use AlexJoffroy\Localization\Tests\TestCase;

class RouteLocalesTest extends TestCase
{
    /** @test */
    public function it_registers_the_locales_macro_on_router()
    {
        $this->assertTrue($this->app['router']->hasMacro('locales'));
    }

    /** @test */
    public function it_registers_locales_routes()
    {
        $router = $this->app['router'];
        $router->locales(function () use ($router) {
            $router->get(trans('routes.posts') . "/{id}", [
                'as' => 'posts.show',
                'uses' => function ($id) {
                    return 'post ' . $id;
                }
            ]);
        });
        
        $this->assertTrue($router->has('en.posts.show'));
        $this->assertTrue($router->has('fr.posts.show'));
        $this->assertEquals('/en/posts/123?foo=bar', $this->app->url->route('posts.show', ['id' => 123, 'foo' => 'bar'], false, 'en'));
        $this->assertEquals('/fr/articles/123?foo=bar', $this->app->url->route('posts.show', ['id' => 123, 'foo' => 'bar'], false, 'fr'));
    }

    /** @test */
    public function it_can_hide_the_default_locale_from_the_url()
    {
        config(['localization.strategy.options.hide_default_locale_in_url' => true]);
        $router = $this->app['router'];
        $router->locales(function () use ($router) {
            $router->get(trans('routes.posts') . "/{id}", [
                'as' => 'posts.show',
                'uses' => function ($id) {
                    return 'post ' . $id;
                }
            ]);
        });

        $this->assertTrue($router->has('en.posts.show'));
        $this->assertTrue($router->has('fr.posts.show'));
        $this->assertEquals('/posts/123?foo=bar', $this->app->url->route('posts.show', ['id' => 123, 'foo' => 'bar'], false, 'en'));
        $this->assertEquals('/fr/articles/123?foo=bar', $this->app->url->route('posts.show', ['id' => 123, 'foo' => 'bar'], false, 'fr'));
    }
}
