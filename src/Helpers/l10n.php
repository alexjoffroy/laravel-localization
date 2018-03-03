<?php

if (!function_exists('l10n')) {
    /**
     * @return \AlexJoffroy\Localization\Localization
     */
    function l10n()
    {
        return app('localization');
    }
}
