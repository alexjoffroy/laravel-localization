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
}
