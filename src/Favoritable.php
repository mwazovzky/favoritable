<?php
namespace Mikewazovzky\Favoritable;

use Mikewazovzky\Favoritable\Models\Favorite;
/**
 * @trait Favoritable
 * allows to favorite/unfavorite \Illuminate\Database\Eloquent\Model
 */
trait Favoritable
{
    /**
     * Deleting Favorites when related model is deleted
     * method is called from Model::boot and sets Model::deleting event handler
     */
    protected static function bootFavoritable()
    {
        static::deleting(function($model) {
            $model->favorites->each->delete();
        }); 
    }   
    /**
     * Get model's favorites
     * 
     * @return Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function favorites()
    {
    	return $this->morphMany(Favorite::class, 'favorite');
    }
    /**
     * Get users who favorited the model
     * 
     * @return Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function favoritedBy()
    {
        return $this->morphToMany('App\User', 'favorite');
    }

    /**
     * Favorite the model by the given user
     * 
     * @param User | null $user
     * @return \App\Favorite
     */
    public function favorite(User $user = null)
    {
    	$userId = $user ? $user->id : auth()->id();

        $attributes = ['user_id' => $userId];

    	if( ! $this->favorites()->where($attributes)->exists()) {
    		return $this->favorites()->create($attributes);
    	}    	
    }
    /**
     * Unfavorite the model by the given user
     * 
     * @param User | null $user
     */
    public function unfavorite(User $user = null)
    {
        $userId = $user ? $user->id : auth()->id();

        $attributes = ['user_id' => $userId];
        // 'Higher Order Collection' is used to make sure 
        // deleting event is called for every Favorite, 
        // as alternative to SQL call (no model event triggered)
        $this->favorites()->where($attributes)->get()->each->delete();
    }
    /**
     * Check if the model is favorited by a User
     *
     * @return boolean
     */
    public function isFavorited(User $user = null)
    {
        $userId = $user ? $user->id : auth()->id();  
        // make SQL query
        // return $this->favorites()->where($attributes)->exists();

        // check pre-loaded relation
        return !! $this->favorites->where('user_id', $userId)->count();
    }
    /**
     * Get isFavorited attribute for the model
     * usage   $model->isFavorited
     * 
     * @return boolean
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
    /**
     * Get favoritesCount attribute for the model
     * usage   $model->favoritesCount
     * 
     * @return integer
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}