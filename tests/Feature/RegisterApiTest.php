<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;

/**
 * Class RegisterApiTest
 * @package Tests\Feature
 * @group Feature
 */
class RegisterApiTest extends BaseTestCase
{
    public function testRegister()
    {
        $data = [
            'name'                  => 'test user',
            'email'                 => 'dummy@email.com',
            'password'              => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(201)
                 ->assertJson([
                     'name'  => $data['name'],
                     'email' => $data['email'],
                 ]);

        $admin = Admin::orderByDesc('id')->first();
        $this->assertEquals($data['name'], $admin->name);
        $this->assertEquals($data['email'], $admin->email);
    }
}
