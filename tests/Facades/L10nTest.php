<?php

namespace AlexJoffroy\RouteLocalization\Tests\Facades;

use AlexJoffroy\RouteLocalization\Facades\L10n;
use AlexJoffroy\RouteLocalization\Tests\TestCase;

class L10nTest extends TestCase
{
    /** @test */
    public function it_resolves_to_the_localization_manager_instance()
    {
        $this->assertEquals(L10n::getFacadeRoot(), app('route-localization'));
    }
}
