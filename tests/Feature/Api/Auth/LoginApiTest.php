<?php
declare(strict_types=1);

namespace Tests\Feature\Api\Auth;

use App\Models\Admin;

/**
 * Class LoginApiTest
 * @package Tests\Feature\Api\Auth
 * @group Feature
 * @group Feature.Api
 * @group Feature.Api.Auth
 */
class LoginApiTest extends BaseTestCase
{
    /** @var Admin $admin */
    private $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(Admin::class)->create();
    }

    public function testLogin()
    {
        $response = $this->json('POST', route('login'), [
            'email'    => $this->admin->email,
            'password' => 'test1234',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'name'  => $this->admin->name,
                     'email' => $this->admin->email,
                 ]);
        $this->assertAuthenticatedAs($this->admin);
    }
}
