<?php

namespace BirdSolutions\Language;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/language.php' => config_path('language.php'),
        ]);

        $this->app->singleton(Language::class, function () {
            return new Language();
        });

        $this->app->singleton('language', function (){
            return new Language();
        });
    }
}