<?php

namespace AlexJoffroy\Localization\Strategies;

use Symfony\Component\HttpFoundation\Request;

class FromUrlPrefix implements Strategy
{
    public function determineLocale(Request $request): string
    {
        return $request->segment(1, '');
    }
}
