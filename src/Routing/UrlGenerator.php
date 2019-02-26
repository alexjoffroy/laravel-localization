<?php

namespace AlexJoffroy\Localization\Routing;

use App;
use Route;
use Config;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use AlexJoffroy\Localization\Localization;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    private $localization;
    
    public function __construct(RouteCollection $routes, Request $request, Localization $localization)
    {
        parent::__construct($routes, $request);

        $this->localization = $localization;
    }
    
    public function route($name, $parameters = [], $absolute = true, $locale = '')
    {
        $locale = $this->localization->isSupportedLocale($locale) ? $locale : $this->localization->getLocale();
        $localesPattern = $this->localization->getSupportedLocalesKeys()->implode('|');
        $name = preg_replace("/^($localesPattern)\./", '', $name);

        return parent::route("$locale.$name", $parameters, $absolute);
    }
}
