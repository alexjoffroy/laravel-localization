<?php

namespace AlexJoffroy\Localization\Tests\Strategies;

use AlexJoffroy\Localization\Tests\TestCase;
use AlexJoffroy\Localization\Strategies\FromDomain;
use AlexJoffroy\Localization\Tests\Concerns\CreatesRequest;

class FromDomainTest extends TestCase
{
    use CreatesRequest;
    
    private static $domains = [
        'fr' => 'nasexpert.test',
        'en' => 'nasexpert-en.test',
    ];

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set([
            'localization.strategy.options.domains' => self::$domains,
        ]);
    }
    
    /** @test */
    public function it_can_determine_locale()
    {
        $strategy = $this->app->make(FromDomain::class);
        
        $request = $this->createRequest('GET', null, '/posts', ['HTTP_HOST' => self::$domains['en']]);
        $this->assertEquals('en', $strategy->determineLocale($request));

        $request = $this->createRequest('GET', null, '/articles', ['HTTP_HOST' => self::$domains['fr']]);
        $this->assertEquals('fr', $strategy->determineLocale($request));
    }
}
