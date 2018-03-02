<?php

if (!function_exists('l10n')) {
    /**
     * @return \AlexJoffroy\RouteLocalization\Manager
     */
    function l10n()
    {
        return app('localization');
    }
}
