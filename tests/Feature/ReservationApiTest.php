<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\GuestDetail;
use App\Models\Room;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/**
 * Class ReservationApiTest
 * @package Tests\Feature
 * @group Feature
 */
class ReservationApiTest extends BaseTestCase
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
            $room = factory(Room::class)->create();
            factory(Reservation::class)->create([
                'guest_id' => $guest->id,
                'room_id'  => $room->id,
            ]);
        });
        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index')
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
        $room        = factory(Room::class)->create();
        $reservation = factory(Reservation::class)->create([
            'guest_id' => $guest->id,
            'room_id'  => $room->id,
        ]);
        $response    = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.show', [
                'reservation' => $reservation->id,
            ])
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'guest_id'   => $reservation->guest_id,
                     'room_id'    => $reservation->room_id,
                     'number'     => $reservation->number,
                     'start_date' => $reservation->start_date,
                     'end_date'   => $reservation->end_date,
                 ]);
    }

    public function testStore()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room = factory(Room::class)->create();
        $this->assertFalse(Reservation::where('number', 2)->exists());

        /** @var Generator $faker */
        $faker    = Factory::create(Config::get('app.faker_locale'));
        $start    = $faker->dateTimeBetween('-10days', '+10days')->format('Y-m-d');
        $end      = $faker->dateTimeBetween($start, $start.'  +4 days')->format('Y-m-d');
        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('reservations.store'),
            [
                'reservations' => [
                    'guest_id'   => $guest->id,
                    'room_id'    => $room->id,
                    'number'     => 2,
                    'start_date' => $start,
                    'end_date'   => $end,
                ],
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'guest_id'   => $guest->id,
                     'room_id'    => $room->id,
                     'number'     => 2,
                     'start_date' => $start,
                     'end_date'   => $end,
                 ]);
        $this->assertTrue(Reservation::where('number', 2)->exists());
    }

    public function testUpdate()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room        = factory(Room::class)->create();
        $reservation = factory(Reservation::class)->create([
            'guest_id' => $guest->id,
            'room_id'  => $room->id,
            'number'   => 1,
        ]);
        $this->assertFalse(Reservation::where('number', 10)->exists());

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('reservations.update', [
                'reservation' => $reservation->id,
            ]),
            [
                'reservations' => [
                    'number' => 10,
                ],
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'number' => 10,
                 ]);
        $this->assertTrue(Reservation::where('number', 10)->exists());
    }

    public function testDestroy()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room        = factory(Room::class)->create();
        $reservation = factory(Reservation::class)->create([
            'guest_id' => $guest->id,
            'room_id'  => $room->id,
            'number'   => 20,
        ]);
        $this->assertTrue(Reservation::where('number', 20)->exists());

        $response = $this->actingAs($this->admin)->json(
            'DELETE',
            route('reservations.destroy', [
                'reservation' => $reservation->id,
            ])
        );

        $response->assertStatus(200);
        $this->assertFalse(Reservation::where('number', 20)->exists());
    }
}
