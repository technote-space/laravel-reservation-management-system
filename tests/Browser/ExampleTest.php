<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Throwable;

class ExampleTest extends DuskTestCase
{
    /**
     * @throws Throwable
     */
    public function testBasicExample()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                        ->assertSee('Laravel');
            }
        );
    }
}
