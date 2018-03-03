<select name="switch" id="switch">
    @foreach($l10n->getSupportedLocales() as $locale => $localeSettings)    
        <option value="{{ $locale }}" {{ $l10n->isCurrentLocale($locale) ? 'selected' : '' }}>
            {{ ucfirst($localeSettings['native']) }}
        </option>
    @endforeach
</select>