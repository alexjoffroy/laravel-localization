<?php

namespace AlexJoffroy\LaravelLocalization;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

class LocalizationManager
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function getLocale(): string
    {
        return $this->app->getLocale();
    }

    public function setLocale(string $locale = '')
    {
        $this->app->setLocale($locale);

        $supportedLocale = $this->getSupportedLocale($locale);
        $constants = collect($supportedLocale->get('constants', []));
        $regionalCode = $supportedLocale->get('regional_code');
        $charset = $supportedLocale->get('charset', false);

        $localeCode = $charset ? "$regionalCode.$charset" : $regionalCode;

        $constants->each(function ($constant) use ($localeCode) {
            setlocale(constant($constant), $localeCode);
        });
    }

    public function isCurrentLocale(string $locale = ''): bool
    {
        return $locale === $this->getLocale();
    }

    public function getSupportedLocale(string $locale = ''): Collection
    {
        $locales = $this->getSupportedLocales();

        if ($locales->has($locale)) {
            return collect($locales->get($locale));
        }

        return collect([]);
    }
    
    public function getSupportedLocales(): Collection
    {
        return collect($this->app->config->get('localization.supported_locales'));
    }

    public function getSupportedLocalesKeys(): Collection
    {
        return $this->getSupportedLocales()->keys();
    }

    public function isSupportedLocale(string $locale = ''): bool
    {
        return $this->getSupportedLocales()->has($locale);
    }

    public function getDefaultLocale(): string
    {
        return $this->app->config->get('localization.default_locale');
    }

    public function isDefaultLocale(string $locale = ''): bool
    {
        return $locale === $this->getDefaultLocale();
    }

    public function shouldHideLocaleInUrl($locale)
    {
        return $this->app->config->get('localization.hide_default_locale_in_url')
            && $this->isDefaultLocale($locale);
    }

    public function route(string $name, array $parameters = [], bool $absolute = true, string $locale = ''): string
    {
        $locale = $this->isSupportedLocale($locale) ? $locale : $this->getLocale();
        $localesPattern = $this->getSupportedLocalesKeys()->implode('|');
        $name = preg_replace("/^($localesPattern)\./", '', $name);

        return $this->app->url->route("$locale.$name", $parameters, $absolute);
    }

    public function currentRoute(string $locale, bool $absolute = true): string
    {
        $request = $this->app->request;
        $routeName = $request->route()->getName();
        $parameters = $request->route()->parameters();
        $url = $this->route($routeName, $parameters, $absolute, $locale);

        if ($query = $request->query()) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }
}
