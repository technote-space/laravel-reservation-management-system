<?php
declare(strict_types=1);

namespace Tests\Feature\Api\Crud;

use App\Models\Admin;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\GuestDetail;
use App\Models\ReservationDetail;
use App\Models\Room;
use App\Models\Setting;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Collection;

/**
 * Class ReservationApiTest
 * @package Tests\Feature\Api\Crud
 * @group Feature
 * @group Feature.Api
 * @group Feature.Api.Crud
 * @SuppressWarnings(PMD.TooManyPublicMethods)
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

    public function testIndex()
    {
        Collection::times(25, function () {
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
                 ->assertJsonStructure([
                     'data',
                     'path',
                     'to',
                     'total',
                 ])
                 ->assertJsonCount(10, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                'per_page' => 5,
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'path',
                     'to',
                     'total',
                 ])
                 ->assertJsonCount(5, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                'count' => 0,
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(25);

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                'count'  => 20,
                'offset' => 20,
            ])
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
        $detail      = factory(ReservationDetail::class)->create([
            'reservation_id' => $reservation->id,
        ]);
        $response    = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.show', [
                'reservation' => $reservation->id,
            ])
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'guest_id'        => $reservation->guest_id,
                     'room_id'         => $reservation->room_id,
                     'start_date_str'  => $reservation->start_date_str,
                     'end_date_str'    => $reservation->end_date_str,
                     'number'          => $detail->number,
                     'room_name'       => $detail->room_name,
                     'guest_name'      => $detail->guest_name,
                     'guest_name_kana' => $detail->guest_name_kana,
                     'guest_zip_code'  => $detail->guest_zip_code,
                     'guest_address'   => $detail->guest_address,
                     'guest_phone'     => $detail->guest_phone,
                 ])
                 ->assertJsonStructure([
                     'is_past',
                     'is_present',
                     'is_future',
                     'days',
                     'charge',
                 ]);
    }

    public function testStore1()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room = factory(Room::class)->create();
        $this->assertFalse(ReservationDetail::where('number', 2)->exists());

        /** @var Generator $faker */
        $faker    = Factory::create(config('app.faker_locale'));
        $start    = $faker->dateTimeBetween('-10days', '+10days')->format('Y-m-d');
        $end      = $faker->dateTimeBetween($start, $start.'  +4 days')->format('Y-m-d');
        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('reservations.store'),
            [
                'reservations'        => [
                    'guest_id'   => $guest->id,
                    'room_id'    => $room->id,
                    'start_date' => $start,
                    'end_date'   => $end,
                ],
                'reservation_details' => [
                    'number'  => 2,
                    'payment' => $room->price,
                ],
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'guest_id'       => $guest->id,
                     'room_id'        => $room->id,
                     'start_date_str' => $start,
                     'end_date_str'   => $end,
                 ]);
        $this->assertTrue(ReservationDetail::where('number', 2)->where('payment', $room->price)->exists());
    }

    public function testStore2()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room     = factory(Room::class)->create();
        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('reservations.store'),
            [
                'reservations'        => [
                    'guest_id'   => $guest->id,
                    'room_id'    => $room->id,
                    'start_date' => now()->format('Y-m-d'),
                    'end_date'   => now()->addDays(5)->format('Y-m-d'),
                ],
                'reservation_details' => [
                    'number' => 1,
                ],
            ]
        );
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'guest_id' => $guest->id,
                     'room_id'  => $room->id,
                 ]);
        $this->assertNull(ReservationDetail::first()->payment);
    }

    public function testFailStore1()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room = factory(Room::class)->create([
            'number' => 2,
        ]);
        $this->assertFalse(Reservation::where('number', 3)->exists());

        /** @var Generator $faker */
        $faker    = Factory::create(config('app.faker_locale'));
        $start    = $faker->dateTimeBetween('-10days', '+10days')->format('Y-m-d');
        $end      = $faker->dateTimeBetween($start, $start.'  +4 days')->format('Y-m-d');
        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('reservations.store'),
            [
                'reservations'        => [
                    'guest_id'   => $guest->id,
                    'room_id'    => $room->id,
                    'start_date' => $start,
                    'end_date'   => $end,
                ],
                'reservation_details' => [
                    'number' => 3,
                ],
            ]
        );

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);
        $this->assertFalse(Reservation::where('number', 3)->exists());
    }

    public function testFailStore2()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room1 = factory(Room::class)->create();
        $room2 = factory(Room::class)->create();

        $today = now()->format('Y-m-d');
        factory(Reservation::class)->create([
            'guest_id'   => $guest->id,
            'room_id'    => $room1->id,
            'start_date' => $today,
            'end_date'   => $today,
        ]);

        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('reservations.store'),
            [
                'reservations' => [
                    'guest_id'   => $guest->id,
                    'room_id'    => $room1->id,
                    'start_date' => $today,
                    'end_date'   => $today,
                ],
            ]
        );
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);

        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('reservations.store'),
            [
                'reservations' => [
                    'guest_id'   => $guest->id,
                    'room_id'    => $room2->id,
                    'start_date' => $today,
                    'end_date'   => $today,
                ],
            ]
        );
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);

        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('reservations.store'),
            [
                'reservations' => [
                    'guest_id'   => $guest->id,
                    'start_date' => $today,
                    'end_date'   => $today,
                ],
            ]
        );
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);
    }

    public function testFailStore3()
    {
        Setting::updateOrCreate([
            'key' => 'max_day',
        ], [
            'value' => 4,
        ]);
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room     = factory(Room::class)->create();
        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('reservations.store'),
            [
                'reservations' => [
                    'guest_id'   => $guest->id,
                    'room_id'    => $room->id,
                    'start_date' => now()->format('Y-m-d'),
                    'end_date'   => now()->addDays(5)->format('Y-m-d'),
                ],
            ]
        );
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);
    }

    public function testUpdate1()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room        = factory(Room::class)->create([
            'number' => 10,
        ]);
        $reservation = factory(Reservation::class)->create([
            'guest_id' => $guest->id,
            'room_id'  => $room->id,
        ]);
        factory(ReservationDetail::class)->create([
            'reservation_id' => $reservation->id,
            'number'         => 1,
        ]);
        $this->assertTrue(ReservationDetail::where('number', 1)->whereNull('payment')->exists());
        $this->assertFalse(ReservationDetail::where('number', 10)->exists());

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('reservations.update', [
                'reservation' => $reservation->id,
            ]),
            [
                'reservation_details' => [
                    'number'  => 10,
                    'payment' => 12345,
                ],
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'number' => 10,
                 ]);
        $this->assertFalse(ReservationDetail::where('number', 1)->exists());
        $this->assertTrue(ReservationDetail::where('number', 10)->where('payment', 12345)->exists());
    }

    public function testUpdate2()
    {
        $room1  = factory(Room::class)->create([
            'name'   => 'room test1',
            'number' => 1,
        ]);
        $room2  = factory(Room::class)->create([
            'name'   => 'room test2',
            'number' => 2,
        ]);
        $guest1 = factory(Guest::class)->create();
        $guest2 = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest1->id,
            'name'     => 'guest test1',
        ]);
        factory(GuestDetail::class)->create([
            'guest_id' => $guest2->id,
            'name'     => 'guest test2',
        ]);
        $reservation = factory(Reservation::class)->create([
            'guest_id' => $guest1->id,
            'room_id'  => $room1->id,
        ]);
        factory(ReservationDetail::class)->create([
            'reservation_id' => $reservation->id,
            'number'         => 1,
            'payment'        => 123,
        ]);
        $this->assertEquals('guest test1', ReservationDetail::find($reservation->id)->guest_name);
        $this->assertEquals('room test1', ReservationDetail::find($reservation->id)->room_name);
        $this->assertEquals(1, ReservationDetail::find($reservation->id)->number);

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('reservations.update', [
                'reservation' => $reservation->id,
            ]),
            [
                'reservations'        => [
                    'guest_id' => $guest2->id,
                    'room_id'  => $room2->id,
                ],
                'reservation_details' => [
                    'payment' => null,
                ],
            ]
        );

        $response->assertStatus(200);
        $this->assertEquals('guest test2', ReservationDetail::find($reservation->id)->guest_name);
        $this->assertEquals('room test2', ReservationDetail::find($reservation->id)->room_name);
        $this->assertEquals(2, ReservationDetail::find($reservation->id)->number);
        $this->assertNull(ReservationDetail::find($reservation->id)->payment);
    }

    public function testFailUpdate1()
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $room        = factory(Room::class)->create([
            'number' => 2,
        ]);
        $reservation = factory(Reservation::class)->create([
            'guest_id' => $guest->id,
            'room_id'  => $room->id,
        ]);
        factory(ReservationDetail::class)->create([
            'reservation_id' => $reservation->id,
            'number'         => 1,
        ]);
        $this->assertTrue(ReservationDetail::where('number', 1)->exists());
        $this->assertFalse(ReservationDetail::where('number', 3)->exists());

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('reservations.update', [
                'reservation' => $reservation->id,
            ]),
            [
                'reservation_details' => [
                    'number' => 3,
                ],
            ]
        );

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);
        $this->assertTrue(ReservationDetail::where('number', 1)->exists());
        $this->assertFalse(ReservationDetail::where('number', 3)->exists());
    }

    public function testFailUpdate2()
    {
        $guest1 = factory(Guest::class)->create();
        $guest2 = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest1->id,
        ]);
        $room1       = factory(Room::class)->create();
        $room2       = factory(Room::class)->create();
        $day1        = now()->format('Y-m-d');
        $day2        = now()->addDay()->format('Y-m-d');
        $day3        = now()->subDay()->format('Y-m-d');
        $reservation = factory(Reservation::class)->create([
            'guest_id'   => $guest1->id,
            'room_id'    => $room1->id,
            'start_date' => $day1,
            'end_date'   => $day1,
        ]);
        factory(Reservation::class)->create([
            'guest_id'   => $guest2->id,
            'room_id'    => $room2->id,
            'start_date' => $day1,
            'end_date'   => $day1,
        ]);
        factory(Reservation::class)->create([
            'guest_id'   => $guest2->id,
            'room_id'    => $room1->id,
            'start_date' => $day2,
            'end_date'   => $day2,
        ]);

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('reservations.update', [
                'reservation' => $reservation->id,
            ]),
            [
                'reservations' => [
                    'end_date' => $day3,
                ],
            ]
        );
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('reservations.update', [
                'reservation' => $reservation->id,
            ]),
            [
                'reservations' => [
                    'start_date' => $day2,
                    'end_date'   => $day2,
                ],
            ]
        );
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);
    }

    public function testFailUpdate3()
    {
        $guest1 = factory(Guest::class)->create();
        $guest2 = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest1->id,
        ]);
        $room1       = factory(Room::class)->create();
        $room2       = factory(Room::class)->create();
        $day1        = now()->format('Y-m-d');
        $day2        = now()->addDay()->format('Y-m-d');
        $reservation = factory(Reservation::class)->create([
            'guest_id'   => $guest1->id,
            'room_id'    => $room1->id,
            'start_date' => $day1,
            'end_date'   => $day1,
        ]);
        factory(Reservation::class)->create([
            'guest_id'   => $guest2->id,
            'room_id'    => $room2->id,
            'start_date' => $day1,
            'end_date'   => $day1,
        ]);
        factory(Reservation::class)->create([
            'guest_id'   => $guest2->id,
            'room_id'    => $room1->id,
            'start_date' => $day2,
            'end_date'   => $day2,
        ]);

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('reservations.update', [
                'reservation' => $reservation->id,
            ]),
            [
                'reservations' => [
                    'room_id' => $room2->id,
                ],
            ]
        );
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('reservations.update', [
                'reservation' => $reservation->id,
            ]),
            [
                'reservations' => [
                    'guest_id' => $guest2->id,
                ],
            ]
        );
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors',
                     'message',
                 ]);
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
        ]);
        factory(ReservationDetail::class)->create([
            'reservation_id' => $reservation->id,
            'number'         => 20,
        ]);
        $this->assertTrue(ReservationDetail::where('number', 20)->exists());

        $response = $this->actingAs($this->admin)->json(
            'DELETE',
            route('reservations.destroy', [
                'reservation' => $reservation->id,
            ])
        );

        $response->assertStatus(200);
        $this->assertFalse(ReservationDetail::where('number', 20)->exists());
    }

    public function testSearch()
    {
        factory(ReservationDetail::class)->create([
            'reservation_id' => factory(Reservation::class)->create([
                'guest_id' => factory(GuestDetail::class)->create([
                    'guest_id'  => factory(Guest::class)->create()->id,
                    'name'      => 'test1',
                    'name_kana' => 'test1',
                    'zip_code'  => '123-4567',
                    'address'   => 'テスト住所1',
                    'phone'     => '012-3456-7890',
                ])->guest_id,
                'room_id'  => factory(Room::class)->create([
                    'name' => 'room1',
                ])->id,
            ])->id,
        ]);
        factory(ReservationDetail::class)->create([
            'reservation_id' => factory(Reservation::class)->create([
                'guest_id' => factory(GuestDetail::class)->create([
                    'guest_id'  => factory(Guest::class)->create()->id,
                    'name'      => 'test2',
                    'name_kana' => 'test2',
                    'zip_code'  => '321-7654',
                    'address'   => 'テスト住所2',
                    'phone'     => '098-7654-3210',
                ])->guest_id,
                'room_id'  => factory(Room::class)->create([
                    'name' => 'room2',
                ])->id,
            ])->id,
        ]);

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index')
        );
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                's' => "\n",
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                's' => 'test',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                's' => 'test1',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                's' => 'test1 room1',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                's' => 'test1 room1 room2',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');
    }

    public function testTermSearch()
    {
        $room1 = factory(Room::class)->create();
        $room2 = factory(Room::class)->create();
        factory(Reservation::class)->create([
            'guest_id'   => factory(GuestDetail::class)->create([
                'guest_id' => factory(Guest::class)->create()->id,
            ])->guest_id,
            'room_id'    => $room1->id,
            'start_date' => '2020-01-01',
            'end_date'   => '2020-01-07',
        ]);

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                'end_date' => '2020-01-01',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                'start_date' => '2020-01-01',
                'end_date'   => '2020-01-08',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                'start_date' => '2020-01-09',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                'start_date' => '2020-01-02',
                'end_date'   => '2020-01-06',
                'room_id'    => $room1->id,
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('reservations.index', [
                'start_date' => '2020-01-02',
                'end_date'   => '2020-01-06',
                'room_id'    => $room2->id,
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');
    }
}
