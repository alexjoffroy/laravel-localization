<?php

if (!function_exists('l10n')) {
    /**
     * @return \App\Localization\LocalizationManager
     */
    function l10n()
    {
        return app('localization');
    }
}
