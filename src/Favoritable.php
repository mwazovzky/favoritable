<?php

namespace Mikewazovzky\Favoritable;

use Mikewazovzky\Favoritable\Models\Favorite;

/**
 * @trait Favoritable
 * allows to favorite/unfavorite objects \Illuminate\Database\Eloquent\Model
 */
trait Favoritable
{
    /**
     * Boot the trait.
     * Add custom attributes that will be appended when model is
     * casted toArray or to JSON object
     * Delete model favorites if model is deleted
     */
    public static function bootFavoritable()
    {
        static::created(function($model) {
            $model->appends = array_merge($model->appends, ['favoritesCount', 'isFavorited']);
        });

        static::deleting(function($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * Get favorites for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * Get users who favorited the model
     *
     * @return Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function favoritedBy()
    {
        return $this->morphToMany('App\User', 'favorited', 'favorites');
    }

    /**
     * Favorite the current model.
     *
     * @param User | null   $user
     * @return Model
     */
    public function favorite($user = null)
    {
        $attributes = [ 'user_id' => $user ? $user->id : auth()->id() ];

        if(! $this->favorites()->where($attributes)->exists() ) {
            return $this->favorites()->create($attributes);
        }

        return null;
    }

    /**
     * Unfavorite the current model.
     *
     * @param User | null   $user
     * @return mixed
     */
    public function unfavorite($user = null)
    {
        $attributes = [ 'user_id' => $user ? $user->id : auth()->id() ];

        return $this->favorites()->where($attributes)->get()->each(function($model) {
            $model->delete();
        });
    }

    /**
     * Determine if the current model has been favorited.
     *
     * @return boolean
     */
    public function isFavorited($user = null)
    {
        $user = $user ?: auth()->user();

        if (!$user) return false;

        return !! $this->favorites->where('user_id', $user->id)->count();
    }

    /**
     * Fetch the favorited status as a property [model->isFavorited].
     *
     * @return bool
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    /**
     * Get the number of favorites for the reply as a property [model->favoritesCount].
     *
     * @return integer
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    // TEMPORARY
    public function favoriteAttributes()
    {
        return json_encode([
            'favoritesCount' => $this->favoritesCount,
            'isFavorited' => $this->isFavorited,
            'id' => $this->id
        ]);
    }
}
