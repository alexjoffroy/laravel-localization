<?php

if (!function_exists('locales')) {
    /**
     * @return \Illuminate\Support\Collection
     */
    function locales()
    {
        return app('localization')->getSupportedLocales();
    }
}
