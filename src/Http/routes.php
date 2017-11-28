<?php

Route::middleware('web')
    ->namespace('MWazovzky\Favoritable\Http')
    ->group(function () {
        Route::post('/favorites/{model}/{id}', 'FavoritesController@store')
            ->name('favorites.store');
        Route::delete('/favorites/{model}/{id}', 'FavoritesController@destroy')
            ->name('favorites.destroy');
    });
