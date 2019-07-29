<?php
declare(strict_types=1);

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Throwable;

class IndexTest extends DuskTestCase
{
    /**
     * @throws Throwable
     */
    public function testIndex()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                        ->waitForText('Reservation')
                        ->assertSee('Reservation')
                        ->screenshot('index');
            }
        );
    }
}
