<?php

namespace AlexJoffroy\Localization\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use AlexJoffroy\Localization\Localization;
use Symfony\Component\HttpFoundation\Response;
use AlexJoffroy\Localization\Strategies\Strategy;

class SetLocaleFromCurrentRoute
{
    /** @var \AlexJoffroy\Localization\Localization */
    protected $localization;

    /** @var \AlexJoffroy\Localization\Strategies\Strategy */
    protected $strategy;

    public function __construct(Localization $localization, Strategy $strategy)
    {
        $this->localization = $localization;
        $this->strategy = $strategy;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->strategy->determineLocale($request);
        
        if (!$this->localization->isSupportedLocale($locale)) {
            $locale = $this->localization->getDefaultLocale();
        }

        $this->localization->setLocale($locale);

        return $next($request);
    }
}
