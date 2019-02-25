<?php

namespace AlexJoffroy\Localization\Strategies;

use Closure;
use Illuminate\Routing\Router;
use AlexJoffroy\Localization\Localization;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Request;

class FromUrlPrefix implements Strategy
{
    /** @var Router */
    private $router;

    /** @var Localization */
    private $localization;
    
    public function __construct(Router $router, Localization $localization)
    {
        $this->router = $router;
        $this->localization = $localization;
    }
    
    public function determineLocale(Request $request): string
    {
        return $request->segment(1, '');
    }

    public function registerRoute(string $locale, callable $closure)
    {
        $prefix = $this->shouldHideLocaleInUrl($locale) ? '' : $locale;
            
        $this->router
            ->as("$locale.")
            ->prefix($prefix)
            ->group($closure);
    }

    private function shouldHideLocaleInUrl($locale): bool
    {
        return $this->localization->config('hide_default_locale_in_url')
            && $this->localization->isDefaultLocale($locale);
    }
}
