<?php

namespace AlexJoffroy\RouteLocalization\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use AlexJoffroy\RouteLocalization\Manager;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** @var \AlexJoffroy\RouteLocalization\Manager */
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1, '');
        if (!$this->manager->isSupportedLocale($locale)) {
            $locale = $this->manager->getDefaultLocale();
        }

        $this->manager->setLocale($locale);

        return $next($request);
    }
}
