**_Currently in active development, first release coming soon_**

# A Laravel package to handle localization from your routes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alexjoffroy/laravel-route-localization.svg?style=flat-square)](https://packagist.org/packages/alexjoffroy/laravel-route-localization)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/alexjoffroy/laravel-route-localization/master.svg?style=flat-square)](https://travis-ci.org/alexjoffroy/laravel-route-localization)
[![Quality Score](https://img.shields.io/scrutinizer/g/alexjoffroy/laravel-route-localization.svg?style=flat-square)](https://scrutinizer-ci.com/g/alexjoffroy/laravel-route-localization)
[![Total Downloads](https://img.shields.io/packagist/dt/alexjoffroy/laravel-route-localization.svg?style=flat-square)](https://packagist.org/packages/alexjoffroy/laravel-route-localization)

This Laravel package gives you the ability to simply handle localization in your application. It provides a set of helpers so you can basically do stuff like:

```bash
GET /en/about # Displays the about page in english
GET /fr/a-propos # Displays the about page in french
```

You can still continue to use Laravel core features such as testing, route caching, lang files, ... 

## Table of content
- [Installation](#installation)
- [Configuration](#configuration)
    - [Default locale](#default-locale)
    - [Supported locales](#supported-locales)
    - [Hide default locale in url](#hide-default-locale-in-url)
- [Usage](#usage)
    - [Register the middleware](#register-the-middleware)
    - [Add your routes](#add-your-routes)
    - [The RouteLocalization instance](#the-routelocalization-instance)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require alexjoffroy/laravel-route-localization
```

This package will automatically register itself.

Optionnaly, you can publish the config file `config/route-localization.php`:

```bash
php artisan vendor:publish --provider="AlexJoffroy\RouteLocalization\RouteLocalizationServiceProvider --tag="config"
```

If you want to customize the [default switch view](#render-switch):
```bash
php artisan vendor:publish --provider="AlexJoffroy\RouteLocalization\RouteLocalizationServiceProvider --tag="views"
```

## Configuration 

### Default locale

The default locale can be changed in the config file. By default, this value is the same as the Laravel `fallback_locale` (set in `config/app.php`).

```php
'default_locale' => config('app.fallback_locale'),
```

### Supported locales

You can list all locales you want to support here. 

Each key should be a locale code (en, fr, ...).

The `native` field is the label which will be rendered in the switch view.
`regional_code`, `charset`, and `constants` fields are required to work with [PHP's setlocale](http://php.net/manual/en/function.setlocale.php), which is called in `RouteLocalization::setLocale`. This is useful for stuff like date formatting with Carbon. 

```php
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
```

### Hide default locale in url

By default, all URLs will be prefixed with the locale. 
```bash
# `en` is default locale
GET /en/about # Displays the about page in english
GET /fr/a-propos # Displays the about page in french
```
When setting `hide_default_locale_in_url` to true, this prefix will be removed for the default locale.

```php
'hide_default_locale_in_url' => false,
```
```bash
# `en` is default locale
GET /about # Displays the about page in english
GET /fr/a-propos # Displays the about page in french
```

## Usage

### Register the middleware
The first step is to register the `SetLocale` middleware into your app. This middleware will guess and set the current locale from the URL. 
The easiest way to do that is to register it globally:
```php
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \AlexJoffroy\RouteLocalization\Http\Middlewares\SetLocale::class,
];
```

### Add your routes
You are now able to register your locales routes, with a convenient helper:
```php
// routes/web.php

Route::locales(function() {
    Route::get(
        trans('routes.about'), 
        'App\Http\Controllers\AboutController@index'
    )->name('about');
});
```

**_Warning: all routes defined inside the `locales` group should be named._**

According you are supporting `en` and `fr` locales and you defined translations for `routes.about`, the above code will register these routes:

| Verb         | URL         | Name     | Action                                     | 
| ------------ |------------ | -------- | ------------------------------------------ |
| GET HEAD     | en/about    | en.about | App\Http\Controllers\AboutController@index |
| GET HEAD     | fr/a-propos | fr.about | App\Http\Controllers\AboutController@index |

### The RouteLocalization instance

The `\AlexJoffroy\RouteLocalization\RouteLocalization` class provides a set of methods which could be helpful to use in your app. The object is registered as a singleton and can be accessed from the app container, the `L10n` facade or the `l10n()` helper.

```php
$l10n = app('route-localization');
// or
$l10n = L10n::getFacadeRoot();
// or
$l10n = l10n();
```

#### Get/Set locale
```php
// Given `en` is the current locale

$l10n->getLocale(); // `en`

$l10n->setLocale('fr'); // Set the current locale to `fr`
```
_`RouteLocalization::getLocale` and `RouteLocalization::setLocale` are aliases for `App::getLocale` and `App::setLocale`_

#### Checks
```php
// Given `en` is the current locale

$l10n->isCurrentLocale('en'); // true

$l10n->isCurrentLocale('not-current'); // false


$l10n->isSupportedLocale('en'); // true

$l10n->isSupportedLocale('not-supported'); // false


// Given `en` is the default locale

$l10n->isDefaultLocale('en'); // true

$l10n->isDefaultLocale('not-default'); // false
```

#### Get config values
```php
$l10n->getSupportedLocales(); // All supported locales (from supported_locales)

$l10n->getSupportedLocale('en'); // Given supported locale (from supported_locales)

$l10n->getSupportedLocaleKeys(); // All supported locale keys (from supported_locales)

$l10n->getDefaultLocale(); // Default locale (from default_locale)


// Given `en` is the default locale

$l10n->shouldHideLocaleInUrl('en'); // True if `hide_default_locale_in_url` is true 

$l10n->shouldHideLocaleInUrl('fr'); // False, even if `hide_default_locale_in_url` is true 
```

#### Generate routes
```php
$l10n->route('about', [], true, 'en'); // `https://yourapp.com/en/about`

$l10n->route('about', [], false, 'en'); // `/en/about`

$l10n->route('about', [], true, 'fr'); // `https://yourapp.com/fr/a-propos`

// Shortcut will fallback to current locale
$l10n->route('about'); // `https://yourapp.com/en/about` 


// Given the current app url is `https://yourapp.com/en/about`

$l10n->currentRoute('fr'); // `https://yourapp.com/fr/a-propos`

$l10n->currentRoute('fr', false); // `/fr/a-propos`
```

#### Render switch

This should be called in a Blade view like `{{ l10n()->renderSwitch() }}`. When using a custom view, you can directly access the RouteLocalization instance from `$l10n` variable.

```php
// Default view
$l10n->renderSwitch();

// Custom view
$l10n->renderSwitch('path.to.view');

// Custom view, with additional data
$l10n->renderSwitch('path.to.view', ['foo' => 'bar']);
```

Custom view example:
```blade
<select name="switch" id="switch">
    @foreach($l10n->getSupportedLocales() as $locale => $localeSettings)    
        <option value="{{ $locale }}" {{ $l10n->isCurrentLocale($locale) ? 'selected' : '' }}>
            {{ ucfirst($localeSettings['native']) }}
        </option>
    @endforeach
</select>
```

#### Facade

The RouteLocalization methods are also available from the `L10n` facade.

```php
L10n::getLocale();
L10n::setLocale();
L10n::route();
L10n::currentRoute();
// etc
```

#### Helpers

```php
// Get the RouteLocalization instance
$l10n = l10n(); 

// Get the current locale
$current = locale(); 

// Set the current locale
locale('en');

// Get supported locale
$supported = locales();
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@alexjoffroy.me instead of using the issue tracker.

## Credits

- [Alex Joffroy](https://github.com/alexjoffroy)
- [All Contributors](../../contributors)

This package was originally build on top of the package skeleton provided by Spatie at [spatie/skeleton-php](https://github.com/spatie/skeleton-php)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

