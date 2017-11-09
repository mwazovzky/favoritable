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

        //  load and make available to the application package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'favoritable');

        // Allow application to publish and modify package assets grouped by asset type tag
        // php artisan vendor:publish --tag=sometag --force

        // --tag=migrations
        // $this->publishes([
        //     __DIR__ . '/../database/migrations' => $this->app->databasePath() . '/migrations'
        // ], 'migrations');

        // --tag=views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/favoritable'),
        ], 'views');

        // --tag=assets
        $this->publishes([
            __DIR__.'/../resources/assets' => resource_path('assets'),
        ], 'assets');
    }

    public function register()
    {
        //
    }
}
