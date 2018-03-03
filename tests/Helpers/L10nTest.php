<?php

namespace AlexJoffroy\Localization\Tests\Helpers;

use AlexJoffroy\Localization\Tests\TestCase;

class L10nTest extends TestCase
{
    /** @test */
    public function it_resolves_to_the_localization_manager_instance()
    {
        $this->assertEquals(l10n(), app('localization'));
    }
}
