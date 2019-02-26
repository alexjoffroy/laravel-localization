<?php

namespace AlexJoffroy\Localization;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class Localization
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function config(string $key)
    {
        return $this->app->config->get("localization.$key");
    }

    public function getLocale(): string
    {
        return $this->app->getLocale();
    }

    public function setLocale(string $locale = '')
    {
        $this->app->setLocale($locale);
    }

    public function isCurrentLocale(string $locale = ''): bool
    {
        return $locale === $this->getLocale();
    }

    public function getSupportedLocale(string $locale = ''): Collection
    {
        $locales = $this->getSupportedLocales();

        if ($locales->has($locale)) {
            return new Collection($locales->get($locale));
        }

        return new Collection([]);
    }
    
    public function getSupportedLocales(): Collection
    {
        return new Collection($this->config('supported_locales'));
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
        return $this->config('default_locale');
    }

    public function isDefaultLocale(string $locale = ''): bool
    {
        return $locale === $this->getDefaultLocale();
    }

    public function route(string $name, array $parameters = [], bool $absolute = true, string $locale = ''): string
    {
        return $this->app->url->route($name, $parameters, $absolute, $locale);
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

    public function renderSwitch(string $view = 'localization::switch', array $data = []): HtmlString
    {
        return new HtmlString(view($view, array_merge($data, [
            'l10n' => $this,
        ]))->render());
    }
}
