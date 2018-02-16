# A Laravel package to handle localization with ease

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alexjoffroy/laravel-localization.svg?style=flat-square)](https://packagist.org/packages/alexjoffroy/laravel-localization)
[![Build Status](https://img.shields.io/travis/alexjoffroy/laravel-localization/master.svg?style=flat-square)](https://travis-ci.org/alexjoffroy/laravel-localization)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/xxxxxxxxx.svg?style=flat-square)](https://insight.sensiolabs.com/projects/xxxxxxxxx)
[![Quality Score](https://img.shields.io/scrutinizer/g/alexjoffroy/laravel-localization.svg?style=flat-square)](https://scrutinizer-ci.com/g/alexjoffroy/laravel-localization)
[![Total Downloads](https://img.shields.io/packagist/dt/alexjoffroy/laravel-localization.svg?style=flat-square)](https://packagist.org/packages/alexjoffroy/laravel-localization)

This Laravel package gives you the ability to simply handle localization in your application. It provides a set of helpers so you can basically do stuff like:

```
GET /en/about # Displays the about page in english
GET /fr/a-propos # Displays the about page in french
```

This package try to fit at its best the Laravel concepts and you can still continue to use core features such as testing, route caching, lang files, ...

## Installation

You can install the package via composer:

```bash
composer require alexjoffroy/laravel-localization
```

## Usage

### Register the middleware
The first step is to register the `SetLocale` middleware to your app. This middleware will guess and set the current locale from the URL. 
The easiest way to do that is to regeister it globally:
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
| GET|HEAD     | en/about    | en.about | App\Http\Controllers\AboutController@index |
| GET|HEAD     | fr/a-propos | fr.about | App\Http\Controllers\AboutController@index |

### API

#### LocalizationManager

The `\AlexJoffroy\LaravelLocalization\LocalizationManager` class provides a set of methods which could be helpful to use in your app. The object is registered as singleton and can be accessed form the app container, the `L10n` facade or `l10n()` helper.

```php
app('localization')->getLocale(); // Get the current locale
app('localization')->setLocale('en'); // Set the current locale to `fr`
app('localization')->isCurrentLocale('en'); // Return true
app('localization')->isCurrentLocale('not-current'); // Return false
app('localization')->getSupportedLocales(); // Return [ 'en' => ['native' => 'English'], 'fr' => ['native' => 'Français']]
app('localization')->getSupportedLocaleKeys(); // Return ['en', 'fr']
app('localization')->isSupportedLocale('en'); // Return true
app('localization')->isSupportedLocale('not-supported'); // Return false
app('localization')->getDefaultLocale(); // Return the default locale set in `config/localization.php`
app('localization')->isDefaultLocale('en'); // Return true
app('localization')->isDefaultLocale('not-default'); // Return false
app('localization')->shouldHideLocaleInUrl('en'); // Return true if `hide_locale_in_url` is set to true in `config/localization.php`

app('localization')->route('about', [], true, 'en'); // Return `https://yourapp.dev/en/about`
app('localization')->route('about', [], false, 'en'); // Return `/en/about`
app('localization')->route('about', [], true, 'fr'); // Return `https://yourapp.dev/fr/a-propos`
// Shortcut will fallback to current locale
app('localization')->route('about'); // Return `https://yourapp.dev/en/about` 

// Current app url is `https://yourapp.dev/en/about`
app('localization')->currentRoute('fr'); // Return `https://yourapp.dev/fr/a-propos`
app('localization')->currentRoute('fr', false); // Return `/fr/a-propos`
```

#### Facade

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

You can list all locales you want to support here. The key is the locale code. The `native` field is label which will be renderer in the switch view.

```php
'supported_locales' => [
    'en' => ['native' => 'English'],
    'fr' => ['native' => 'Français'],
],
```

### Hide default locale in url

By default, the package will prefix all URLs with the locale. Set this value to true, allows the package to remove this prefix for the default locale.

```php
'hide_default_locale_in_url' => false,
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

