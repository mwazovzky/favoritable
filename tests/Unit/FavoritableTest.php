<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use MWazovzky\Favoritable\Models\FavoritableModel;

class FavoritableTest extends TestCase
{
    use DatabaseMigrations;

    protected $dummy;

    public static function setUpBeforeClass()
    {
        //
    }

    protected function setUp()
    {
        parent::setUp();

        FavoritableModel::createTable();

        $this->dummy = FavoritableModel::create(['name' => 'Mary']);
    }

    protected function tearDown()
    {
        FavoritableModel::deleteTable();
    }

    /** @test */
    function it_can_create_dummy_model()
    {
        $this->assertDatabaseHas('favoritable_models', [
            'name' => $this->dummy->name
        ]);
    }

    /** @test */
    function guest_can_not_favorite_model()
    {
        try {
            $this->dummy->favorite();
        } catch (\Exception $e) {
            // Do something ... with Integrity constrain violation
        }

        $this->assertDatabaseMissing('favorites', [
            'favorited_id' => $this->dummy->id,
        ]);
    }

    /** @test */
    function authenticated_user_can_favorite_model()
    {
        $this->signIn();
        $this->dummy->favorite();

        $this->assertDatabaseHas('favorites', [
            'user_id' => auth()->id(),
            'favorited_id' => $this->dummy->id,
            'favorited_type' => get_class($this->dummy),
        ]);
    }

    /** @test */
    function user_can_check_if_model_is_favorited()
    {
        $this->signIn();
        $this->dummy->favorite();
        $this->assertTrue($this->dummy->isFavorited());
    }

    /** @test */
    function model_has_isFavorited_attribute()
    {
        $this->signIn();
        $this->dummy->favorite();
        $this->assertTrue($this->dummy->isFavorited);
    }

    /** @test */
    function model_has_favoritesCount_attribute()
    {
        $this->signIn();
        $this->dummy->favorite();
        $this->assertEquals(1, $this->dummy->favoritesCount);

        $this->signIn();
        $this->dummy->favorite();
        $this->assertEquals(2, $this->dummy->fresh()->favoritesCount);

        $this->dummy->unfavorite();
        $this->assertEquals(1, $this->dummy->fresh()->favoritesCount);
    }

    /** @test */
    function user_can_not_favorite_model_twice()
    {
        $this->signIn();

        $this->dummy->favorite();
        $this->assertEquals(1, $this->dummy->favoritesCount);

        $this->dummy->favorite();
        $this->assertEquals(1, $this->dummy->fresh()->favoritesCount);
    }

    /** @test */
    function favorites_are_deleted_when_model_is_deleted()
    {
        $this->signIn();
        $id = $this->dummy->id;
        $this->dummy->favorite();

        $this->assertDatabaseHas('favorites', [
            'favorited_id' => $id,
        ]);

        $this->dummy->delete();

        $this->assertDatabaseMissing('favorites', [
            'favorited_id' => $id,
        ]);
    }

    /** @test */
    function model_can_get_the_list_of_users_who_favorited_it()
    {
        $user = $this->signIn();
        $this->dummy->favorite();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
        ]);

        $this->assertTrue(
            $this->dummy->favoritedBy->contains($user)
        );
    }

    /** @test */
    public function model_provides_its_favorite_attributes()
    {
        $user = $this->signIn();
        $this->dummy->favorite();
        $this->assertEquals(json_encode([
            'favoritesCount' => $this->dummy->favoritesCount,
            'isFavorited' => $this->dummy->isFavorited,
            'id' => $this->dummy->id,
        ]), $this->dummy->favoriteAttributes());
    }

    protected function signIn()
    {
        $user = factory('App\User')->create();
        $this->be($user);
        return $user;
    }
}
