> **Currently in active development, first release coming soon **

# A Laravel package to handle localization with ease

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alexjoffroy/laravel-localization.svg?style=flat-square)](https://packagist.org/packages/alexjoffroy/laravel-localization)
[![Build Status](https://img.shields.io/travis/alexjoffroy/laravel-localization/master.svg?style=flat-square)](https://travis-ci.org/alexjoffroy/laravel-localization)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/xxxxxxxxx.svg?style=flat-square)](https://insight.sensiolabs.com/projects/xxxxxxxxx)
[![Quality Score](https://img.shields.io/scrutinizer/g/alexjoffroy/laravel-localization.svg?style=flat-square)](https://scrutinizer-ci.com/g/alexjoffroy/laravel-localization)
[![Total Downloads](https://img.shields.io/packagist/dt/alexjoffroy/laravel-localization.svg?style=flat-square)](https://packagist.org/packages/alexjoffroy/laravel-localization)

This Laravel package gives you the ability to simply handle localization in your application. It provides a set of helpers so you can basically do stuff like:

```bash
GET /en/about # Displays the about page in english
GET /fr/a-propos # Displays the about page in french
```

You can still continue to use core features such as testing, route caching, lang files, ... 

## Table of content
- [Installation](#installation)
- [Configuration](#configuration)
    - [Default locale](#default-locale)
    - [Supported locales](#supported-locales)
    - [Hide default locale in url](#hide-default-locale-in-url)
- [Usage](#usage)
    - [Register the middleware](#register-the-middleware)
    - [Add your routes](#add-your-routes)
    - [API](#api)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require alexjoffroy/laravel-localization
```

## Configuration 

This package will automatically register service provider and facade.

To publish the config file `config/localization.php` run

```bash
php artisan vendor:publish --provider="AlexJoffroy\LaravelLocalization\Providers\LocalizationServiceProvider"
```

### Default locale

The default locale can be changed in the config file. By default, this value is the same as the Laravel `fallback_locale` (set in `config/app.php`).

```php
'default_locale' => config('app.fallback_locale'),
```

### Supported locales

You can list all locales you want to support here. 

Each key should be a locale code (en, fr, ...).

The `native` field is the label which will be rendered in the switch view.
`regional_code`, `charset`, and `constants` fields are required to work with [PHP's setlocale](http://php.net/manual/en/function.setlocale.php), which is called in `LocalizationManager::setLocale`. This is useful for stuff like date formatting with Carbon. 

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

By default, the package will prefix all URLs with the locale. Set this value to true, allows the package to remove this prefix for the default locale.

```php
'hide_default_locale_in_url' => false,
```

## Usage

### Register the middleware
The first step is to register the `SetLocale` middleware to your app. This middleware will guess and set the current locale from the URL. 
The easiest way to do that is to register it globally:
```php
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \AlexJoffroy\LaravelLocalization\Http\Middlewares\SetLocale::class,
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

> Warning: all routes defined inside the `locales` group should be named.

According you are supporting `en` and `fr` locales and you defined translations for `routes.about`, the above code will register these routes:

| Verb         | URL         | Name     | Action                                     | 
| ------------ |------------ | -------- | ------------------------------------------ |
| GET HEAD     | en/about    | en.about | App\Http\Controllers\AboutController@index |
| GET HEAD     | fr/a-propos | fr.about | App\Http\Controllers\AboutController@index |

### API

#### LocalizationManager

The `\AlexJoffroy\LaravelLocalization\LocalizationManager` class provides a set of methods which could be helpful to use in your app. The object is registered as singleton and can be accessed form the app container, the `L10n` facade or `l10n()` helper.

```php
$l10n = app('localization');

$l10n->getLocale(); // Get the current locale

$l10n->setLocale('en'); // Set the current locale to `fr`

$l10n->isCurrentLocale('en'); // Return true

$l10n->isCurrentLocale('not-current'); // Return false

$l10n->getSupportedLocales(); // Return config value (supported_locales)

$l10n->getSupportedLocale('en'); // Return config value for specific locale

$l10n->getSupportedLocaleKeys(); // Return ['en', 'fr']

$l10n->isSupportedLocale('en'); // Return true

$l10n->isSupportedLocale('not-supported'); // Return false

$l10n->getDefaultLocale(); // Return the default locale set in `config/localization.php`

$l10n->isDefaultLocale('en'); // Return true

$l10n->isDefaultLocale('not-default'); // Return false

$l10n->shouldHideLocaleInUrl('en'); // Return true if `hide_locale_in_url` is set to true in `config/localization.php`

$l10n->route('about', [], true, 'en'); // Return `https://yourapp.dev/en/about`

$l10n->route('about', [], false, 'en'); // Return `/en/about`

$l10n->route('about', [], true, 'fr'); // Return `https://yourapp.dev/fr/a-propos`

// Shortcut will fallback to current locale
$l10n->route('about'); // Return `https://yourapp.dev/en/about` 

// Current app url is `https://yourapp.dev/en/about`
$l10n->currentRoute('fr'); // Return `https://yourapp.dev/fr/a-propos`

$l10n->currentRoute('fr', false); // Return `/fr/a-propos`
```

#### Facade

The LocalizationManager methods are also available from the `L10n` facade.

```php
L10n::getLocale();
L10n::setLocale();
L10n::route();
L10n::currentRoute();
// etc
```

#### Helpers

```php
// Get the LocalizationManager instance
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

