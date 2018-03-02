<?php

namespace AlexJoffroy\RouteLocalization\Tests\Helpers;

use AlexJoffroy\RouteLocalization\Tests\TestCase;

class LocaleTest extends TestCase
{
    /** @test */
    public function it_can_get_the_current_locale()
    {
        $this->assertEquals('en', locale());
        config(['app.locale' => 'fr']);
        $this->assertEquals('fr', locale());
    }

    /** @test */
    public function it_can_set_the_current_locale()
    {
        locale('en');
        $this->assertEquals('en', locale());
        locale('fr');
        $this->assertEquals('fr', locale());
    }
}
