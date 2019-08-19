<?php
declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use Tests\BaseTestCase;

/**
 * Class SummaryApiTest
 * @package Tests\Feature\Api\Auth
 * @group Feature
 * @group Feature.Api
 * @group Feature.Api.Summary
 */
class SummaryApiTest extends BaseTestCase
{
    private function createReservation($start, $end, $price)
    {
        $room = Room::where('price', $price)->first();
        if (! $room) {
            $room = factory(Room::class)->create([
                'price' => $price,
            ]);
        }
        factory(Reservation::class)->create([
            'guest_id'   => factory(Guest::class)->create(),
            'room_id'    => $room->id,
            'start_date' => $start,
            'end_date'   => $end,
        ]);
    }

    public function testSummaryMonthly()
    {
        $this->createReservation('2019-12-31', '2019-12-31', 1);      // 2019/12 * 1
        $this->createReservation('2019-12-31', '2020-01-01', 10);     // 2019/12 * 2
        $this->createReservation('2020-01-01', '2020-01-01', 100);    // 2020/01 * 1
        $this->createReservation('2020-01-31', '2020-01-31', 1000);   // 2020/01 * 1
        $this->createReservation('2020-01-31', '2020-02-01', 10000);  // 2020/01 * 2
        $this->createReservation('2020-02-01', '2020-02-01', 100000); // 2020/02 + 1
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->json('GET', route('summary', [
            'start_date' => '2019-11-01',
            'end_date'   => '2020-04-01',
        ]));
        $response->assertStatus(200)
                 ->assertExactJson([
                     '2019-11' => 0,
                     '2019-12' => 1 * 1 + 10 * 2,
                     '2020-01' => 100 * 1 + 1000 * 1 + 10000 * 2,
                     '2020-02' => 100000 * 1,
                     '2020-03' => 0,
                 ]);
    }

    public function testSummaryDaily()
    {
        $this->createReservation('2020-01-02', '2020-01-02', 1);   // 2020/01/02 * 1
        $this->createReservation('2020-01-02', '2020-01-03', 10);  // 2020/01/02 * 2
        $this->createReservation('2020-01-03', '2020-01-04', 100); // 2020/01/03 * 2
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->json('GET', route('summary', [
            'start_date' => '2020-01-01',
            'end_date'   => '2020-01-05',
            'type'       => 'daily',
            'room_id'    => '',
        ]));
        $response->assertStatus(200)
                 ->assertExactJson([
                     '2020-01-01' => 0,
                     '2020-01-02' => 1 * 1 + 10 * 2,
                     '2020-01-03' => 100 * 2,
                     '2020-01-04' => 0,
                 ]);
    }

    public function testRoomSummaryMonthly()
    {
        $this->createReservation('2019-12-31', '2019-12-31', 1);      // 2019/12 * 1
        $this->createReservation('2019-12-31', '2020-01-01', 10);     // 2019/12 * 2
        $this->createReservation('2020-01-01', '2020-01-01', 100);    // 2020/01 * 1
        $this->createReservation('2020-01-31', '2020-01-31', 1000);   // 2020/01 * 1
        $this->createReservation('2020-01-31', '2020-02-01', 10000);  // 2020/01 * 2
        $this->createReservation('2020-02-01', '2020-02-01', 100000); // 2020/02 + 1
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->json('GET', route('summary', [
            'start_date' => '2019-11-01',
            'end_date'   => '2020-04-01',
            'room_id'    => 1,
        ]));
        $response->assertStatus(200)
                 ->assertExactJson([
                     '2019-11' => 0,
                     '2019-12' => 1 * 1,
                     '2020-01' => 0,
                     '2020-02' => 0,
                     '2020-03' => 0,
                 ]);

        $response = $this->actingAs($admin)->json('GET', route('summary', [
            'start_date' => '2019-11-01',
            'end_date'   => '2020-04-01',
            'room_id'    => 5,
        ]));
        $response->assertStatus(200)
                 ->assertExactJson([
                     '2019-11' => 0,
                     '2019-12' => 0,
                     '2020-01' => 10000 * 2,
                     '2020-02' => 0,
                     '2020-03' => 0,
                 ]);
    }

    public function testRoomSummaryDaily()
    {
        $this->createReservation('2020-01-02', '2020-01-02', 1);   // 2020/01/02 * 1
        $this->createReservation('2020-01-02', '2020-01-03', 10);  // 2020/01/02 * 2
        $this->createReservation('2020-01-03', '2020-01-04', 100); // 2020/01/03 * 2
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->json('GET', route('summary', [
            'start_date' => '2020-01-01',
            'end_date'   => '2020-01-05',
            'type'       => 'daily',
            'room_id'    => 1,
        ]));
        $response->assertStatus(200)
                 ->assertExactJson([
                     '2020-01-01' => 0,
                     '2020-01-02' => 1 * 1,
                     '2020-01-03' => 0,
                     '2020-01-04' => 0,
                 ]);

        $response = $this->actingAs($admin)->json('GET', route('summary', [
            'start_date' => '2020-01-01',
            'end_date'   => '2020-01-05',
            'type'       => 'daily',
            'room_id'    => 3,
        ]));
        $response->assertStatus(200)
                 ->assertExactJson([
                     '2020-01-01' => 0,
                     '2020-01-02' => 0,
                     '2020-01-03' => 100 * 2,
                     '2020-01-04' => 0,
                 ]);
    }
}
