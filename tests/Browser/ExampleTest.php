<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    use  DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $category = $this->createCategory();

        $this->browse(function (Browser $browser) use ($category) {
            $browser->visit('/')
                ->assertSee('Categorías')
                ->screenshot('TestBasicExample');
        });
    }
}
