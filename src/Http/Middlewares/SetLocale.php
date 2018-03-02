<?php

namespace AlexJoffroy\RouteLocalization\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use AlexJoffroy\RouteLocalization\RouteLocalization;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** @var \AlexJoffroy\RouteLocalization\RouteLocalization */
    protected $localization;

    public function __construct(RouteLocalization $localization)
    {
        $this->localization = $localization;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1, '');
        
        if (!$this->localization->isSupportedLocale($locale)) {
            $locale = $this->localization->getDefaultLocale();
        }

        $this->localization->setLocale($locale);

        return $next($request);
    }
}
