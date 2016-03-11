<?php

namespace App\Providers;

use App\CustomProvider;
use Dingo\Api\Auth\Provider\Basic;
use Dingo\Api\Auth\Provider\OAuth2;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        app('Dingo\Api\Auth\Auth')->extend('basic', function ($app) {
           return new Basic($app['auth'], 'email');
        });

        // app('Dingo\Api\Auth\Auth')->extend('custom', function ($app) {
        //     return new CustomProvider;
        // });

        // app('Dingo\Api\Auth\Auth')->extend('oauth', function ($app) {
        //    $provider = new OAuth2($app['oauth2-server.authorizer']->getChecker());

        //     $provider->setUserResolver(function ($id) {
        //         // Logic to return a user by their ID.
        //     });

        //     $provider->setClientResolver(function ($id) {
        //         // Logic to return a client by their ID.
        //     });

        //     return $provider;
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
