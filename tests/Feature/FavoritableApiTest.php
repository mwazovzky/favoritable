<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mikewazovzky\Favoritable\Models\FavoritableModel;

class FavoritableApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $dummy;

    protected function setUp()
    {
        parent::setUp();

        config(['app.name' => 'testing_favoritable']);

        FavoritableModel::createTable();

        $this->dummy = FavoritableModel::create(['name' => 'Mary']);
    }

    protected function tearDown()
    {
        FavoritableModel::deleteTable();
    }

    /** @test */
    public function guest_may_not_favorite_a_post()
    {
        $this->postJson(route('favorites.store', ['model' => 'Some Model', 'id' => 333]))
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_may_favorite_model()
    {
        // Given we have an autenticated user and model
        $this->signIn();

        // When we post to "favorites" endpoint
        $modelType = kebab_case((new \ReflectionClass($this->dummy))->getShortName());
        $this->postJson(route('favorites.store', ['model' => $modelType, 'id' => $this->dummy->id]))
            ->assertStatus(200);

        // Then it should be recorded in data base
        $this->assertDatabaseHas('favorites', [
            'favorited_type' => get_class($this->dummy),
            'favorited_id' => $this->dummy->id
        ]);
        // .. and it can fetch post favortes
        $this->assertCount(1, $this->dummy->favorites);
    }

    /** @test */
    public function guest_may_not_unfavorite_post()
    {
        $this->deleteJson(route('favorites.destroy', ['model' => 'someType', 'id' => 333]))
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_may_unfavorite_post()
    {
        // Given we have a post favorited by the user
        $this->signIn();
        $this->dummy->favorite(auth()->user());
        $this->assertCount(1, $this->dummy->fresh()->favorites);

        // When we post to "unfavorite" endpoint
        $modelType = kebab_case((new \ReflectionClass($this->dummy))->getShortName());
        $this->deleteJson(route('favorites.destroy', ['model' => $modelType, 'id' => $this->dummy->id]))
            ->assertStatus(200);

        // Then it should be deleted from database
        $this->assertCount(0, $this->dummy->favorites);
    }

    protected function signIn()
    {
        $user = factory('App\User')->create();
        $this->be($user);
        return $user;
    }
}
