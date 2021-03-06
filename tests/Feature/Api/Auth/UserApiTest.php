<?php
declare(strict_types=1);

namespace Tests\Feature\Api\Auth;

use App\Models\Admin;

/**
 * Class UserApiTest
 * @package Tests\Feature\Api\Auth
 * @group Feature
 * @group Feature.Api
 * @group Feature.Api.Auth
 */
class UserApiTest extends BaseTestCase
{
    /** @var Admin $admin */
    private $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(Admin::class)->create();
    }

    public function testLoggedIn()
    {
        $response = $this->actingAs($this->admin)->json('GET', route('user'));

        $response->assertStatus(200)
                 ->assertJson([
                     'name'  => $this->admin->name,
                     'email' => $this->admin->email,
                 ]);
        $this->assertAuthenticatedAs($this->admin);
    }

    public function testNotLoggedIn()
    {
        $response = $this->json('GET', route('user'));

        $response->assertStatus(200);
        $this->assertEquals('', $response->content());
    }
}
