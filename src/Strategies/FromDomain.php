<?php

namespace AlexJoffroy\Localization\Strategies;

use Illuminate\Routing\Router;
use AlexJoffroy\Localization\Localization;
use Symfony\Component\HttpFoundation\Request;

class FromDomain implements Strategy
{
    private $localization;

    private $router;

    public function __construct(Router $router, Localization $localization)
    {
        $this->localization = $localization;
        $this->router = $router;
    }
    
    public function determineLocale(Request $request): string
    {
        $host = $request->getHost();

        return array_flip($this->getDomains())[$host];
    }

    public function registerRoute(string $locale, callable $closure)
    {
        $this->router
            ->domain($this->getDomains()[$locale])
            ->as("$locale.")
            ->group($closure);
    }
    
    private function getDomains()
    {
        return $this->localization->config('strategy.options.domains');
    }
}
