<?php

return[

    'default_locale' => config('app.fallback_locale'),

    'supported_locales' => [
        'en' => ['native' => 'English'],
        'fr' => ['native' => 'Français'],
    ],

    'hide_default_locale_in_url' => false,
    
];
