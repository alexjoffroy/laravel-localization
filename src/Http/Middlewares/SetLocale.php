<?php

namespace AlexJoffroy\LaravelLocalization\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use AlexJoffroy\LaravelLocalization\LocalizationManager;

class SetLocale
{
    /** @var \AlexJoffroy\LaravelLocalization\LocalizationManager */
    protected $localization;

    public function __construct(LocalizationManager $localization)
    {
        $this->localization = $localization;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1, '');
        if (!$this->localization->isSupportedLocale($locale)) {
            $locale = $this->localization->getDefaultLocale();
        }

        $this->localization->setLocale($locale);

        return $next($request);
    }
}
