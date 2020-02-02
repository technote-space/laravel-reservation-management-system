<?php
declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\Guest;
use App\Models\GuestDetail;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\Room;
use App\Models\Setting;
use Carbon\Carbon;
use Tests\BaseTestCase;

/**
 * Class CheckoutApiTest
 * @package Tests\Feature\Api\Auth
 * @group Feature
 * @group Feature.Api
 * @group Feature.Api.Reservation
 */
class CheckoutApiTest extends BaseTestCase
{
    private function createReservation(string $start, string $end, string $checkout, int $price)
    {
        $room = Room::where('price', $price)->first();
        if (! $room) {
            $room = factory(Room::class)->create([
                'price' => $price,
            ]);
        }
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        $detail = factory(ReservationDetail::class)->create([
            'reservation_id' => factory(Reservation::class)->create([
                'guest_id'   => $guest->id,
                'room_id'    => $room->id,
                'start_date' => $start,
                'end_date'   => $end,
                'checkout'   => $checkout,
            ])->id,
        ]);
        $detail->paid();
    }

    public function testCheckoutListToBeEmpty()
    {
        $today = Carbon::today();
        $this->createReservation($today->copy()->subDays(3)->format('Y-m-d'), $today->copy()->subDay()->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->subDays(2)->format('Y-m-d'), $today->copy()->addDay()->format('Y-m-d'), '10:00:00', 10);
        $this->createReservation($today->copy()->subDays(1)->format('Y-m-d'), $today->copy()->addDays(2)->format('Y-m-d'), '12:00:00', 100);
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->json('GET', route('checkout'));
        $response->assertStatus(200)
                 ->assertExactJson([]);
    }

    public function testCheckoutList()
    {
        Setting::create([
            'key'   => 'checkin',
            'value' => '15:00',
            'type'  => 'time',
        ]);
        $today = Carbon::today();
        $this->createReservation($today->copy()->subDays(3)->format('Y-m-d'), $today->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->subDays(2)->format('Y-m-d'), $today->format('Y-m-d'), '09:00:00', 10);
        $this->createReservation($today->copy()->subDays(1)->format('Y-m-d'), $today->format('Y-m-d'), '11:00:00', 100);
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->json('GET', route('checkout'));
        $response->assertStatus(200);

        $json = json_decode($response->content(), true);
        $this->assertCount(3, $json);
        $this->assertEquals($today->format('Y-m-d 00:00:00'), $json[0]['end_date']);
        $this->assertEquals('09:00:00', $json[0]['checkout']);
        $this->assertEquals($today->format('Y-m-d 00:00:00'), $json[1]['end_date']);
        $this->assertEquals('10:00:00', $json[1]['checkout']);
        $this->assertEquals($today->format('Y-m-d 00:00:00'), $json[2]['end_date']);
        $this->assertEquals('11:00:00', $json[2]['checkout']);
    }
}
