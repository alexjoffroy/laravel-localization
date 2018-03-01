<?php

namespace AlexJoffroy\LaravelLocalization\Tests\Helpers;

use AlexJoffroy\LaravelLocalization\Tests\TestCase;

class LocalesTest extends TestCase
{
    /** @test */
    public function it_can_get_the_supported_locale()
    {
        $this->assertEquals(collect($this->locales), locales());
    }
}
