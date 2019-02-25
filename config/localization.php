<?php

use AlexJoffroy\Localization\Strategies\FromUrlPrefix;

return[

    'default_locale' => config('app.fallback_locale'),

    'supported_locales' => [
        'en' => [
            'native' => 'English',
            'regional_code' => 'en_GB',
            'charset' => 'UTF-8',
            'constants' => ['LC_TIME'],
        ],
        'fr' => [
            'native' => 'Français',
            'regional_code' => 'fr_FR',
            'charset' => 'UTF-8',
            'constants' => ['LC_TIME'],
        ],
    ],

    'strategy' => [
        'class' => FromUrlPrefix::class,

        'options' => [
            'hide_default_locale_in_url' => false,
        ]
    ],

    
];
