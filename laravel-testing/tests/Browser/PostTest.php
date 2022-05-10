<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\Login;
use Tests\Browser\Components\DatePicker;

class PostTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {   
        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                ->assertSee('Post');
        });
    }

    // menguji halaman membuat post
    public function testCreateVisible()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                ->clickLink('Create post')
                ->type('title', "TestCreatePost2")
                ->type('description', "TestCreatePostDescription2")
                ->press('submit-post')
                ->assertSee('TestCreatePost2');
        });
    }
    // menggunakan dust component untuk datePicker
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->within(new DatePicker, function ($browser) {
                        $browser->selectDate(2022, 5, 07);
                    })
                    ->assertSee('May');
        });
    }
    // untuk buka page tertentu menggunakan login
    public function testLogin(){
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
            ->login('user','password')
            ->assertSee('Successfully Login');
        });
    }
    
    
}
