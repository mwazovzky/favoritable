<?php

namespace Mikewazovzky\Favoritable\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    /**
     * Auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'favorited_id', 'favorited_type'];

    /**
     * Fetch the model that was favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favorited()
    {
        return $this->morphTo();
    }
}
