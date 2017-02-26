<?php

namespace BirdSolutions\Language\Test;

use BirdSolutions\Language\Facades\Language;
use BirdSolutions\Language\Middleware\LanguageMiddleware;
use BirdSolutions\Language\Providers\LanguageServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Include package providers in testing.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LanguageServiceProvider::class,
        ];
    }

    /**
     * Include package aliases in testing.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Language' => Language::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app->make('Illuminate\Contracts\Http\Kernel')
            ->pushMiddleware('Illuminate\Session\Middleware\StartSession');

        $app['config']->set('language.default_locale', 'en');
        $app['config']->set('language.available_locales', ['en' => 'English', 'ar' => 'Arabic']);

        $app->call([$this, 'mapRoutes']);
    }

    /**
     * Set up some application routes for testing purposes.
     */
    public function mapRoutes()
    {
        app('router')->group([
            'middleware' => [LanguageMiddleware::class],
        ], function () {
            app('router')->get('/', function () {
                return;
            });
        });

        app('router')->group([
            'prefix' => '{locale}',
            'middleware' => [LanguageMiddleware::class],
        ], function () {
            app('router')->get('/', function () {
                return;
            });
            app('router')->get('/about', function () {
                return;
            });
            app('router')->get('/contact', function () {
                return;
            });
            app('router')->get('/contact/{about}', function () {
                return;
            });
            app('router')->post('/save', function () {
                return 'saved';
            });
            app('router')->put('/save', function () {
                return 'saved';
            });
            app('router')->patch('/save', function () {
                return 'saved';
            });
            app('router')->delete('/save', function () {
                return 'saved';
            });
        });
    }
}