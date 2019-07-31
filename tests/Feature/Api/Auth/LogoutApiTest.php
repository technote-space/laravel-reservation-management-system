<?php
declare(strict_types=1);

namespace Tests\Feature\Api\Auth;

use App\Models\Admin;
use Tests\Feature\BaseTestCase;

/**
 * Class LogoutApiTest
 * @package Tests\Feature\Api\Auth
 * @group Feature
 * @group Feature.Api
 * @group Feature.Api.Auth
 */
class LogoutApiTest extends BaseTestCase
{
    /** @var Admin $admin */
    private $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(Admin::class)->create();
    }

    public function testLogout()
    {
        $response = $this->actingAs($this->admin)->json('POST', route('logout'));

        $response->assertStatus(200);
        $this->assertGuest();
        $this->assertEquals('""', $response->content());
    }
}
