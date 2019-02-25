<?php

namespace AlexJoffroy\Localization\Strategies;

use Symfony\Component\HttpFoundation\Request;

interface Strategy
{
    public function determineLocale(Request $request): string;
}
