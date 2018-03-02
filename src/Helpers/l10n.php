<?php

if (!function_exists('l10n')) {
    /**
     * @return \AlexJoffroy\RouteLocalization\RouteLocalization
     */
    function l10n()
    {
        return app('route-localization');
    }
}
