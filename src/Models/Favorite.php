<?php
namespace Mikewazovzky\Favoritable\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    /**
     * Table name for the model, requiired if table name is not plurified model name
     */
    // protected $table = 'favorites';
    /**
     * Allow mass assignment for the model
     */
    protected $fillable = ['user_id'];
    /**
     * Get related favorited model
     * 
	 * @return Illuminate\Database\Eloquent\Relations\morphTo
     */
    public function favorited()
    {
    	return $this->morphTo();
    }
}