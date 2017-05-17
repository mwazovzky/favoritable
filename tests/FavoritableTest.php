<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritableTest extends TestCase
{
    use DatabaseMigrations;

    public static function setUpBeforeClass()
    {
        echo 'Mikewazovzky\Favoritable Unit Tests '; 
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
}