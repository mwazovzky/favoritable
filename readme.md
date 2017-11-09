[![Build Status](https://travis-ci.org/mikewazovzky/favoritable.svg?branch=master)](https://travis-ci.org/mikewazovzky/favoritable)
[![Coverage Status](https://coveralls.io/repos/github/mikewazovzky/favoritable/badge.svg?branch=master&foo=bar)](https://coveralls.io/github/mikewazovzky/favoritable?branch=master)

<h2 align="center">
	<img src="https://laravel.com/assets/img/components/logo-laravel.svg">
</h2>

### Project:
mikewazovzky\favoritable
### Description
Laravel Package allows app User to Favorite/Unfavorite Eloquent Model instance
#### Version: 0.0.4
#### Change log:
0.0.4 routes and controller to favorite/unfavorite model added<br>
0.0.3 package autodiscovery (as of Laravel 5.5)<br>
0.0.2 added Model::favoritedBy() and User::favoritedModels() methods that define Many To Many Polymorphic Relations<br>
0.0.1 initial project scaffolding<br>
#### Documentation
See PHPDoc blocks in the code
#### Installation.
1. Pull the package into Laravel project,
```
composer require mikewazovzky/favoritable
```
2. Register package service provider at `app.php` for Laravel 5.4 or below.
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
3. Run database migration to create 'favorites' table
```
$ php artisan migrate
```
4. Use trait Favoritable for every Model that can be favorited by a User.
Check trait docblocks for a list of available methods.
```
use \Mikewazovzky\Favoritable\Favoritable
```
5. Model favorite/unfavorite endpoint are available as related routes are added to 'web' route group
```
Route::post('/favorites/{model}/{id}', 'FavoritesController@store')->name('favorites.store');
Route::delete('/favorites/{model}/{id}', 'FavoritesController@destroy')->name('favorites.destroy');
```
where `model` and `id` are short model class name (use `kebab-case` for `KebabCase` class name) and
`id` for favorited/unfavorited model.
6. View `favoritable::favorite` is available and can be used as `favorites` vidget .
```
// file /resources/views/.../template.blade.php
@include('favoritable::favorite')
```
7. Vue '<favorite>' component may be published to `/resources/assets/js/components/favoritable/Favorite.vue`
by command
```
$ php artisan vendor:publish --provider=FavoritableServiceProvider --tag=assets
```
You can register component
```
// file /resources/assets/js/app.js
Vue.component('favorite', require('./components/Favorite.vue'));
```
and use it.

8. Optionally add User::favoritedModels method for every Model that can be favorited
```
/**
 * Get all of the models that are favorited by this user.
 *
 * @return Illuminate\Database\Eloquent\Relations\morphedByMany
 */
public function favoritedModels()
{
    return $this->morphedByMany('App\Model', 'favorite');
}
```
