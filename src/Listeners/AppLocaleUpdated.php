<?php

namespace AlexJoffroy\Localization\Listeners;

use Illuminate\Foundation\Events\LocaleUpdated;
use AlexJoffroy\Localization\Localization;

class AppLocaleUpdated
{
    /** @var \AlexJoffroy\Localization\Localization */
    protected $localization;
    
    public function __construct(Localization $localization)
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
