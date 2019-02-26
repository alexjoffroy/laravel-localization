<?php

namespace AlexJoffroy\Localization\Tests\Strategies;

use AlexJoffroy\Localization\Tests\TestCase;
use AlexJoffroy\Localization\Strategies\FromUrlPrefix;
use AlexJoffroy\Localization\Tests\Concerns\CreatesRequest;

class FromUrlPrefixTest extends TestCase
{
    use CreatesRequest;
    
    /** @test */
    public function it_can_determine_locale()
    {
        $strategy = $this->app->make(FromUrlPrefix::class);
        
        $request = $this->createRequest('GET', null, '/en/posts');
        $this->assertEquals('en', $strategy->determineLocale($request));

        $request = $this->createRequest('GET', null, '/fr/posts');
        $this->assertEquals('fr', $strategy->determineLocale($request));
    }
}
