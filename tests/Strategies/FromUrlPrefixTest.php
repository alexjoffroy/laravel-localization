<?php

namespace AlexJoffroy\Localization\Strategies;

use Illuminate\Http\Request;
use AlexJoffroy\Localization\Tests\TestCase;
use AlexJoffroy\Localization\Strategies\FromUrlPrefix;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class FromUrlPrefixTest extends TestCase
{
    /** @test */
    public function it_can_determine_locale()
    {
        $strategy = $this->app->make(FromUrlPrefix::class);
        
        $request = $this->createRequest('GET', null, '/en/posts');
        $this->assertEquals('en', $strategy->determineLocale($request));

        $request = $this->createRequest('GET', null, '/fr/posts');
        $this->assertEquals('fr', $strategy->determineLocale($request));
    }

    private function createRequest(
        $method = 'GET',
        $content = null,
        $uri = '/',
        $server = [],
        $parameters = [],
        $cookies = [],
        $files = []
    ) {
        return (new Request)->createFromBase(
            SymfonyRequest::create(
                $uri,
                $method,
                $parameters,
                $cookies,
                $files,
                $server,
                $content
            )
        );
    }
}
