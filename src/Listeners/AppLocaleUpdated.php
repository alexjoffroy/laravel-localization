<?php

namespace AlexJoffroy\RouteLocalization\Listeners;

use Illuminate\Foundation\Events\LocaleUpdated;
use AlexJoffroy\RouteLocalization\RouteLocalization;

class AppLocaleUpdated
{
    /** @var \AlexJoffroy\RouteLocalization\RouteLocalization */
    protected $localization;
    
    public function __construct(RouteLocalization $localization)
    {
        $this->localization = $localization;
    }

    public function handle(LocaleUpdated $event)
    {
        $supportedLocale = $this->localization->getSupportedLocale($event->locale);
        $constants = collect($supportedLocale->get('constants', []));
        $regionalCode = $supportedLocale->get('regional_code');
        $charset = $supportedLocale->get('charset', false);

        $localeCode = $charset ? "$regionalCode.$charset" : $regionalCode;

        $constants->each(function ($constant) use ($localeCode) {
            setlocale(constant($constant), $localeCode);
        });
    }
}
