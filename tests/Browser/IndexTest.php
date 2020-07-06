<?php
declare(strict_types=1);

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Throwable;

class IndexTest extends DuskTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        if (! file_exists(__DIR__.DIRECTORY_SEPARATOR.'screenshots'.DIRECTORY_SEPARATOR.'index')) {
            mkdir(__DIR__.DIRECTORY_SEPARATOR.'screenshots'.DIRECTORY_SEPARATOR.'index', 0777, true);
        }
    }

    /**
     * @throws Throwable
     */
    public function testIndex()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->assertSee('Reservation')
                        ->assertSee(date('Y'))
                        ->screenshot('index/index');
            }
        );
    }

    /**
     * @throws Throwable
     */
    public function testFormValidation()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->screenshot('index/form')
                        ->type('form input[type="text"]', 'test')
                        ->type('form input[type="password"]', 'pass')
                        ->click('.v-input__icon--append button')
                        ->assertDisabled('form button')
                        ->assertSee('The E-Mail field must be a valid email')
                        ->assertSee('The Password field must be at least 8 characters')
                        ->screenshot('index/validation');
            }
        );
    }

    /**
     * @throws Throwable
     */
    public function testFailLogin()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->type('form input[type="text"]', 'test@example.com')
                        ->type('form input[type="password"]', 'test12345678')
                        ->click('.v-input__icon--append button')
                        ->press('LOGIN')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->assertSee('ログインに失敗しました。')
                        ->screenshot('index/fail-login');
            }
        );
    }

    /**
     * @throws Throwable
     */
    public function testLogin()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->type('form input[type="text"]', 'test@example.com')
                        ->type('form input[type="password"]', 'test1234')
                        ->click('.v-input__icon--append button')
                        ->press('LOGIN')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->assertTitle('Dashboard')
                        ->assertPresent('.v-app-bar__nav-icon i')
                        ->screenshot('index/login1')
                        ->visit('/')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->screenshot('index/login2');
            }
        );
    }

    /**
     * @throws Throwable
     */
    public function testDrawer()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->type('form input[type="text"]', 'test@example.com')
                        ->type('form input[type="password"]', 'test1234')
                        ->press('LOGIN')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->assertDontSee('test name')
                        ->click('.v-app-bar__nav-icon i')
                        ->waitFor('.v-navigation-drawer__content')
                        ->assertSee('test name')
                        ->screenshot('index/drawer')
                        ->click('.mdi-logout')
                        ->waitUntilMissing('.v-overlay--active', 10)
                        ->assertPresent('form')
                        ->screenshot('index/logout');
            }
        );
    }
}
