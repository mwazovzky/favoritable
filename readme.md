[![Build Status](https://travis-ci.org/mwazovzky/favoritable.svg?branch=master)](https://travis-ci.org/mwazovzky/favoritable)[![[![Coverage Status](https://coveralls.io/repos/github/mwazovzky/favoritable/badge.svg?branch=master)](https://coveralls.io/github/mwazovzky/favoritable?branch=master)

<h2 align="center">
	<img src="https://laravel.com/assets/img/components/logo-laravel.svg">
</h2>

### Project: mikewazovzky\favoritable

### Description
Laravel Package. Allows app User to favorite/unfavorite Eloquent Model instance.

#### Version: 0.0.6
#### Change log:
0.0.6 frontend assets added: `<favorite-vidget>` vue component<br>
0.0.5 frontend assets added: `<favorite>` vue component and `favorite` vidget (blade partial)<br>
0.0.4 routes and controller to favorite/unfavorite model added<br>
0.0.3 package auto discovery (as of Laravel 5.5)<br>
0.0.2 added Model::favoritedBy() methods that define Many-To-Many Polymorphic Relationships<br>
0.0.1 initial project scaffolding<br>

#### Installation.

1. Pull the package into Laravel project
```
composer require mikewazovzky/favoritable
```

2. For Laravel 5.4 or below register package service provider at `/config/app.php`.<br>
Package will be auto-registered for Laravel 5.5 and above.
```
// file config/app.php

...
'providers' => [
...
\Mikewazovzky\Favoritable\FavoritableServiceProvider::class
...
];
...
```

3. Run database migration to create `favorites` table
```
$ php artisan migrate
```

4. Use trait Favoritable for every Model that can be favorited by a User.<br>
Check trait docblocks for a list of available methods.
```
use \Mikewazovzky\Favoritable\Favoritable;
```

5. Package makes `favorite`/`unfavorite` endpoints available for the application via
adding corresponding routes to 'web' routes group
```
Route::post('/favorites/{model}/{id}', 'FavoritesController@store')->name('favorites.store');
Route::delete('/favorites/{model}/{id}', 'FavoritesController@destroy')->name('favorites.destroy');
```
where `model` and `id` are short model class name (`kebab-case` for `KebabCase`) and
id for the model being favorited/unfavorited.<br>

6. Run artisan command to publish package assets to
 `/resources/assets/js/components/favoritable/Favorite.vue` folder:
```
$ php artisan vendor:publish --tag=assets
```
7. Published vue component are:
`<favorite>` - favorite/unfavorite button
`<favorite-vidget>` toggles `favorite` query string attribute.
8. Register components:
```
// file /resources/assets/js/app.js

Vue.component('favorite', require('./components/favoritable/Favorite.vue'));
Vue.component('favorite-vidget', require('./components/favoritable/FavoriteVidget.vue'));
```
Component usage
```
<favorite type="modelClass" :model={{ $model->favoriteAttributes() }}></favorite>
```
where<br>
`modelClass` is a short model class name (use `kebab-case` for `KebabCase`),
`$model` is a model instance,<br>
`Model::favoriteAttributes()` is a method provided by `Favoritable` trait.<br>
Any object (e.g. model itself) that has: `id`, `isFavoreted` and `favoritesCount`
fields may be passed as component `model` property.
```
<favorite-vidget></favorite-vidget>
```
Favorite vidget requires no parameters.
