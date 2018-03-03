<ul>
    @foreach($l10n->getSupportedLocales() as $locale => $localeSettings)
    <li>
        @if ($l10n->isCurrentLocale($locale))
            {{ ucfirst($localeSettings['native']) }}            
        @else
        <a rel="alternate" hreflang="{{ $locale }}" href="{{ $l10n->currentRoute($locale) }}">
            {{ ucfirst($localeSettings['native']) }}
        </a>
        @endif
    </li>
    @endforeach
</ul>