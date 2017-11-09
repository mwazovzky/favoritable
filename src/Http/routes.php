<?php

Route::group(['middleware' => ['web']], function () {

    Route::post('/favorites/{model}/{id}', 'Mikewazovzky\Favoritable\Http\FavoritesController@store')
        ->name('favorites.store');

    Route::delete('/favorites/{model}/{id}', 'Mikewazovzky\Favoritable\Http\FavoritesController@destroy')
        ->name('favorites.destroy');
});
