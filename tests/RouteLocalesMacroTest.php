<?php

namespace AlexJoffroy\LaravelLocalization\Tests;

use AlexJoffroy\LaravelLocalization\LocalizationManager;

class RouteLocalesMacroTest extends TestCase
{
    protected $locales = [
        'en' => ['native' => 'English'],
        'fr' => ['native' => 'Français'],
    ];
    
    /** @test */
    public function it_registers_the_locales_macro_on_router()
    {
        $this->assertTrue($this->app['router']->hasMacro('locales'));
    }

    /** @test */
    public function it_registers_locales_routes()
    {
        config([
            'localization.supported_locales' => $this->locales,
            'localization.default_locale' => 'en'
        ]);
        $this->app['translator']->addLines(['routes.home' => 'home'], 'en');
        $this->app['translator']->addLines(['routes.home' => 'accueil'], 'fr');
            
        $router = $this->app['router'];
        $router->locales(function () use ($router) {
            $router->get(trans('routes.home'), [
                'as' => 'home',
                'uses' => function () {
                    return 'home';
                }
            ]);
        });
        
        $this->assertTrue($router->has('en.home'));
        $this->assertTrue($router->has('fr.home'));
        $this->assertEquals('/en/home', route('en.home', [], false));
        $this->assertEquals('/fr/accueil', route('fr.home', [], false));
    }
}
