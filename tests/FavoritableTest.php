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
}