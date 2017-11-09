<?php

namespace Mikewazovzky\Favoritable\Http;

use Illuminate\Routing\Controller as BaseController;

class FavoritesController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // dd(auth()->check());

        $this->middleware('auth');
    }

    /**
     * Store a new favorite in the database.
     *
     * @param  Post $reply
     */
    public function store($modelType, $modelId)
    {
        $this->getModel($modelType, $modelId)->favorite(auth()->user());

        if (request()->expectsJson()) {
            return response(['status' => 'Post favorited']);
        }

        return back();
    }

    /**
     * Delete the favorite.
     *
     * @param Post $post
     */
    public function destroy($modelType, $modelId)
    {
        $this->getModel($modelType, $modelId)->unfavorite(auth()->user());

        if (request()->expectsJson()) {
            return response(['status' => 'Post unfavorited']);
        }

        return back();
    }

    /**
     * Find the model of requested type with requested id
     *
     * @param string $type
     * @param integer $id
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function getModel($type, $id)
    {
        $namespace = (app()->environment() === 'testing') ? '\\Mikewazovzky\\Favoritable\\Models\\' : '\\App\\';

        $class = $namespace . studly_case($type);

        return  $class::findOrFail($id);
    }
}
