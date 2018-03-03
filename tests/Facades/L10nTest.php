<?php

namespace AlexJoffroy\Localization\Tests\Facades;

use AlexJoffroy\Localization\Facades\L10n;
use AlexJoffroy\Localization\Tests\TestCase;

class L10nTest extends TestCase
{
    /** @test */
    public function it_resolves_to_the_localization_manager_instance()
    {
        $this->assertEquals(L10n::getFacadeRoot(), app('localization'));
    }
}
