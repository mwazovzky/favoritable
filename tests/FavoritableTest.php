<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritableTest extends TestCase
{
    use DatabaseMigrations;
    protected $dummy;

    public static function setUpBeforeClass()
    {
        echo 'Mikewazovzky\Favoritable Unit Tests '; 
    }

    protected function setUp()
    {
        parent::setUp();
        \Dummy::createTable();           
        $this->dummy = \Dummy::create(['name' => 'Mary']); 
    }

    protected function tearDown()
    {
        \Dummy::deleteTable();           
    }    

    /** @test */
    function it_does_something()
    {		
		$this->assertTrue(true);
    }

    /** @test */
    function it_can_see_test_page()
    {
        $this->get('favorites/test')
            ->assertSee('test');
    }   
    
    /** @test */
    function it_can_read_config_data_and_dispay_it_on_name_page()
    {
		$name = config('mikewazovzky-favorites.name');	
		$this->get('favorites/name')
			->assertSee($name);
    }   

    /** @test */
    function it_can_create_dummy_model() 
    {
        $this->assertDatabaseHas('dummies', [
            'name' => $this->dummy->name
        ]);
    }   
    /** @test */
    function guest_can_not_favorite_model() 
    {
        try {
            $this->dummy->favorite();
        } catch (\Exception $e) {
            // Do something ... 
        }
        
        $this->assertDatabaseMissing('favorites', [
            'favorited_id' => $this->dummy->id,
        ]);  
    }   

    /** @test */
    function user_can_favorite_model() 
    {
        $this->signIn();
        $this->dummy->favorite();
        $this->assertDatabaseHas('favorites', [
            'user_id' => Auth()->id(),
            'favorited_id' => $this->dummy->id,
            // 'favorited_type' => ...
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
}