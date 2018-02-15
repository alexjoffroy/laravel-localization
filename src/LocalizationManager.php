<?php

namespace AlexJoffroy\LaravelLocalization;

use Illuminate\Contracts\Container\Container;

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
        return $this->app->setLocale($locale);
    }

    public function isCurrentLocale(string $locale = ''): bool
    {
        return $locale === $this->getLocale();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getSupportedLocales()
    {
        return collect($this->app['config']->get('localization.supported_locales'));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getSupportedLocalesKeys()
    {
        return $this->getSupportedLocales()->keys();
    }

    public function isSupportedLocale(string $locale = ''): bool
    {
        return $this->getSupportedLocales()->has($locale);
    }

    public function getDefaultLocale(): string
    {
        return $this->app['config']->get('localization.default_locale');
    }

    public function isDefaultLocale(string $locale = ''): bool
    {
        return $locale === $this->getDefaultLocale();
    }

    public function route(string $name, array $parameters = [], bool $absolute = true, string $locale = ''): string
    {
        $locale = $this->isSupportedLocale($locale) ? $locale : $this->getLocale();
        $localesPattern = $this->getSupportedLocalesKeys()->implode('|');
        $name = preg_replace("/^($localesPattern)\./", '', $name);

        return $this->app['url']->route("$locale.$name", $parameters, $absolute);
    }
}
