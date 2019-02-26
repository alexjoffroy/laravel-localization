<?php

namespace AlexJoffroy\Localization\Tests\Routing;

use AlexJoffroy\Localization\Tests\TestCase;
use AlexJoffroy\Localization\Routing\UrlGenerator;

class UrlGeneratorTest extends TestCase
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

        $this->urlGenerator = new UrlGenerator(
            $router->getRoutes(),
            $this->app->request,
            $this->app->localization
        );
    }

    /** @test */
    public function it_can_generate_a_localized_route()
    {
        $this->assertEquals(url('/en/posts/123'), $this->urlGenerator->route('posts.show', ['id' => 123], true, 'en'));
        $this->assertEquals('/en/posts/123', $this->urlGenerator->route('posts.show', ['id' => 123], false, 'en'));
        $this->assertEquals(url('/fr/articles/123'), $this->urlGenerator->route('posts.show', ['id' => 123], true, 'fr'));
        $this->assertEquals('/fr/articles/123', $this->urlGenerator->route('posts.show', ['id' => 123], false, 'fr'));
    }

    /** @test */
    public function it_can_generate_a_localized_route_guessing_the_current_locale()
    {
        $this->app->setLocale('en');
        $this->assertEquals(url('/en/posts/123'), $this->urlGenerator->route('posts.show', ['id' => 123], true));
        $this->assertEquals('/en/posts/123', $this->urlGenerator->route('posts.show', ['id' => 123], false));

        $this->app->setLocale('fr');
        $this->assertEquals(url('/fr/articles/123'), $this->urlGenerator->route('posts.show', ['id' => 123], true));
        $this->assertEquals('/fr/articles/123', $this->urlGenerator->route('posts.show', ['id' => 123], false));
    }

    /** @test */
    public function it_can_generate_a_localized_route_from_a_not_supported_locale()
    {
        $this->app->setLocale('en');
        $this->assertEquals(url('/en/posts/123'), $this->urlGenerator->route('posts.show', ['id' => 123], true, 'not-supported'));
        $this->assertEquals('/en/posts/123', $this->urlGenerator->route('posts.show', ['id' => 123], false, 'not-supported'));

        $this->app->setLocale('fr');
        $this->assertEquals(url('/fr/articles/123'), $this->urlGenerator->route('posts.show', ['id' => 123], true, 'not-supported'));
        $this->assertEquals('/fr/articles/123', $this->urlGenerator->route('posts.show', ['id' => 123], false, 'not-supported'));
    }

    /** @test */
    public function it_can_generate_a_localized_route_from_a_localized_route_name()
    {
        $this->app->setLocale('en');
        $this->assertEquals(url('/fr/articles/123'), $this->urlGenerator->route('en.posts.show', ['id' => 123], true, 'fr'));
        $this->assertEquals('/fr/articles/123', $this->urlGenerator->route('en.posts.show', ['id' => 123], false, 'fr'));
    }
}
