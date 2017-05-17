<?php

Route::get('favorites/test', function () {
    return 'test';
});


Route::get('favorites/name/{name?}', '\Mikewazovzky\Favoritable\Http\FavoritableController@name');