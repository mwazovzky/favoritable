<?php

namespace Mikewazovzky\Favoritable;

use Illuminate\Support\ServiceProvider;

class FavoritableServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // load and make available to the application package routes
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        //  load and make available to the application package migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Allow application to publish and modify package assets grouped by asset type tag
        // php artisan vendor:publish --tag=sometag --force
        // --tag=migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');
    }

    public function register()
    {
        //
    }
}
