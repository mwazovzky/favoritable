<?php
namespace Mikewazovzky\Favoritable\Http;

use Illuminate\Routing\Controller as BaseController;

class FavoritableController extends BaseController
{
    public function name($name = null)
    {
        $name = $name ?: config('mikewazovzky-favorites.name');
        
        return view('mikewazovzky-favoritable::name', compact('name'));
    }    
}