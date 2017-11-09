<?php

namespace Mikewazovzky\Favoritable\Models;

use Illuminate\Database\Eloquent\Model;

class FavoritableModel extends Model
{
    use \Mikewazovzky\Favoritable\Favoritable;

    protected static $tableName = 'favoritable_models';

    protected $guarded = [];

    public static function createTable()
    {

        if (\Schema::hasTable(self::$tableName)) {
            return;
        }

        // dd(self::$tableName);

        \Schema::create(self::$tableName, function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public static function deleteTable()
    {
        \Schema::dropIfExists(self::$tableName);
    }
}
