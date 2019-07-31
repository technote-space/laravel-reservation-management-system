<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Guest;
use App\Models\GuestDetail;
use Illuminate\Support\Collection;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Facades\Config;

/**
 * Class GuestApiTest
 * @package Tests\Feature
 * @group Feature
 */
class GuestApiTest extends BaseTestCase
{
    /** @var Admin $admin */
    private $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(Admin::class)->create();
    }

    protected static function seeder()
    {
    }

    public function testIndex()
    {
        Collection::times(5, function () {
            $guest = factory(Guest::class)->create();
            factory(GuestDetail::class)->create([
                'guest_id' => $guest->id,
            ]);
        });
        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('guests.index')
        );

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    public function testShow()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('guests.show', [
                'guest' => $guest->id,
            ])
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => $guest->detail->name,
                 ]);
    }

    public function testStore()
    {
        $this->assertFalse(GuestDetail::where('name', 'abc')->exists());

        /** @var Generator $faker */
        $faker    = Factory::create(Config::get('app.faker_locale'));
        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('guests.store'),
            [
                'guest_details' => [
                    'name'      => 'abc',
                    'name_kana' => $faker->kanaName,
                    'zip_code'  => substr_replace($faker->postcode, '-', 3, 0),
                    'address'   => preg_replace('#\A\d+\s+#', '', $faker->address),
                    'phone'     => '0'.$faker->numberBetween(10, 99).'-'.$faker->numberBetween(10, 9999).'-'.$faker->numberBetween(100, 9999),
                ],
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                     'created_at',
                 ]);
        $this->assertTrue(GuestDetail::where('name', 'abc')->exists());
    }

    public function testUpdate()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
            'name'     => 'def',
            'address'  => 'xyz',
        ]);
        $this->assertFalse(GuestDetail::where('name', 'efg')->where('address', 'xyz')->exists());

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('guests.update', [
                'guest' => $guest->id,
            ]),
            [
                'guest_details' => [
                    'name' => 'efg',
                ],
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                     'created_at',
                 ]);
        $this->assertTrue(GuestDetail::where('name', 'efg')->where('address', 'xyz')->exists());
    }

    public function testDestroy()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
            'name'     => 'fgh',
        ]);
        $this->assertTrue(GuestDetail::where('name', 'fgh')->exists());

        $response = $this->actingAs($this->admin)->json(
            'DELETE',
            route('guests.destroy', [
                'guest' => $guest->id,
            ])
        );

        $response->assertStatus(200);
        $this->assertFalse(GuestDetail::where('name', 'fgh')->exists());
    }
}
