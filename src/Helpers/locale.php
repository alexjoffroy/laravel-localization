<?php

if (!function_exists('locale')) {
    /**
     * @return null|string
     */
    function locale($locale = null)
    {
        $l10n = app('route-localization');
        if ($locale) {
            return $l10n->setLocale($locale);
        }

        return $l10n->getLocale();
    }
}
