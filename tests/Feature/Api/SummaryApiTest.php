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
        $this->createReservation('2019-12-31', '2019-12-31', 0x1);   // 2019/12
        $this->createReservation('2019-12-31', '2020-01-01', 0x2);   // 2019/12
        $this->createReservation('2020-01-01', '2020-01-01', 0x4);   // 2020/01
        $this->createReservation('2020-01-31', '2020-01-31', 0x8);   // 2020/01
        $this->createReservation('2020-01-31', '2020-02-01', 0x10);  // 2020/01
        $this->createReservation('2020-02-01', '2020-02-01', 0x20);  // 2020/02

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('GET', route('summary', [
            'start_date' => '2019-11-01',
            'end_date'   => '2020-04-01',
        ]));

        $response->assertStatus(200)
                 ->assertExactJson([
                     '2019-11' => 0,
                     '2019-12' => 0x1 | 0x2,
                     '2020-01' => 0x4 | 0x8 | 0x10,
                     '2020-02' => 0x20,
                     '2020-03' => 0,
                 ]);
    }

    public function testSummaryDaily()
    {
        $this->createReservation('2020-01-02', '2020-01-02', 0x1);   // 2020/01/02
        $this->createReservation('2020-01-02', '2020-01-03', 0x2);   // 2020/01/02
        $this->createReservation('2020-01-03', '2020-01-04', 0x4);   // 2020/01/03

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('GET', route('summary', [
            'start_date' => '2020-01-01',
            'end_date'   => '2020-01-05',
            'type'       => 'daily',
        ]));

        $response->assertStatus(200)
                 ->assertExactJson([
                     '2020-01-01' => 0,
                     '2020-01-02' => 0x1 | 0x2,
                     '2020-01-03' => 0x4,
                     '2020-01-04' => 0,
                 ]);
    }
}
