# Laravel Route Localization Package

This package will help you localize your routes, set the language from the routes and add some nice functionality to it.

**Installation**

composer require birdsolutions/laravel-language

**Service Provider**
Add the service provider to the config/app.php file

```$xslt
[   
    /*
     * Package Service Providers...
     */
    BirdSolutions\Language\Providers\LanguageServiceProvider::class,
]
```

**Facade**
```$xslt
'aliases' => [
    ...
    'Language' => BirdSolutions\Language\Facades\Language::class,
]
```

**Config**
Publish the configuration file.
```
    php artisan vendor:publish --provider="BirdSolutions\Language\Providers\LanguageServiceProvider"

```

**Middleware**
Add the language Middleware to the routeMiddleware array in kernel.php

```$xslt
protected $routeMiddleware = [
       ...
        'language' => \BirdSolutions\Language\Middleware\LanguageMiddleware::class,
    ];
```

 **Routes**
```$xslt
Route::group([
        'prefix' => \Language::getLocale(),
        'middleware' => 'language'
    ], function () {
        // Add Localized routes here.
    });
    
```

**Change Language Manually**
```$xslt
    Route::any('/language', '\BirdSolutions\Language\Controllers\LanguageController@changeLanguage');
```

It will accept an input of language and set the language to the desired, you can send in get or post inputs.
```$xslt
    <a href='/language?language=en'>English</a>
```