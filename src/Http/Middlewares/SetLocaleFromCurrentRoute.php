<?php

namespace AlexJoffroy\Localization\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use AlexJoffroy\Localization\Localization;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromCurrentRoute
{
    /** @var \AlexJoffroy\Localization\Localization */
    protected $localization;

    public function __construct(Localization $localization)
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
