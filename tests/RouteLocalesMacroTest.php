<?php

namespace AlexJoffroy\LaravelLocalization\Tests;

use Illuminate\Support\Facades\Route;
use AlexJoffroy\LaravelLocalization\LocalizationManager;

class RouteLocalesMacroTest extends TestCase
{
    /** @test */
    public function it_register_the_locales_macro_on_router()
    {
        $this->assertTrue($this->app['router']->hasMacro('locales'));
    }
}
