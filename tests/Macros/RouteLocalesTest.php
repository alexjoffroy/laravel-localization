<?php

namespace AlexJoffroy\LaravelLocalization\Tests\Macros;

use AlexJoffroy\LaravelLocalization\Tests\TestCase;
use AlexJoffroy\LaravelLocalization\LocalizationManager;

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
        $this->assertEquals('/en/posts/123?foo=bar', route('en.posts.show', ['id' => 123, 'foo' => 'bar'], false));
        $this->assertEquals('/fr/articles/123?foo=bar', route('fr.posts.show', ['id' => 123, 'foo' => 'bar'], false));
    }
}
