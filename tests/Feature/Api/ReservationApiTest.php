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
    protected static function seeder(): void
    {
        Setting::create([
            'key'   => 'checkin',
            'value' => '15:00',
            'type'  => 'time',
        ]);
    }

    /**
     * @param  string  $start
     * @param  string  $end
     * @param  string  $checkout
     * @param  int  $price
     *
     * @return Reservation
     */
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

        $reservation = factory(Reservation::class)->create([
            'guest_id'   => $guest->id,
            'room_id'    => $room->id,
            'start_date' => $start,
            'end_date'   => $end,
            'checkout'   => $checkout,
        ]);
        factory(ReservationDetail::class)->create([
            'reservation_id' => $reservation->id,
        ]);

        return $reservation;
    }

    public function testCheckinListToBeEmpty()
    {
        $today = Carbon::today();
        $this->createReservation($today->copy()->subDay()->format('Y-m-d'), $today->copy()->addDays(1)->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->addDays(2)->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '09:00:00', 10);
        $this->createReservation($today->copy()->addDays(1)->format('Y-m-d'), $today->copy()->addDays(2)->format('Y-m-d'), '11:00:00', 100);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('GET', route('reservation.checkin.list', ['date' => '']));
        $response->assertStatus(200)
                 ->assertExactJson([]);
    }

    public function testCheckoutListToBeEmpty()
    {
        $today = Carbon::today();
        $this->createReservation($today->copy()->subDays(1)->format('Y-m-d'), $today->copy()->subDay()->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->subDays(3)->format('Y-m-d'), $today->copy()->addDay()->format('Y-m-d'), '09:00:00', 10);
        $this->createReservation($today->copy()->subDays(2)->format('Y-m-d'), $today->copy()->addDays(2)->format('Y-m-d'), '11:00:00', 100);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('GET', route('reservation.checkout.list', ['date' => '']));
        $response->assertStatus(200)
                 ->assertExactJson([]);
    }

    public function testCheckinList1()
    {
        $today = Carbon::today();
        $this->createReservation($today->format('Y-m-d'), $today->copy()->addDays(1)->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '09:00:00', 10);
        $this->createReservation($today->format('Y-m-d'), $today->copy()->addDays(2)->format('Y-m-d'), '11:00:00', 100);
        $this->createReservation($today->copy()->subDays(1)->format('Y-m-d'), $today->copy()->addDays(1)->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->addDays(1)->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '10:00:00', 1);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('GET', route('reservation.checkin.list'));
        $response->assertStatus(200);

        $json = json_decode($response->content(), true);
        $this->assertCount(3, $json);
        $this->assertEquals($today->format('Y-m-d 00:00:00'), $json[0]['start_date']);
        $this->assertEquals($today->format('Y-m-d 00:00:00'), $json[1]['start_date']);
        $this->assertEquals($today->format('Y-m-d 00:00:00'), $json[2]['start_date']);
    }

    public function testCheckinList2()
    {
        $today = Carbon::today();
        $this->createReservation($today->format('Y-m-d'), $today->copy()->addDays(1)->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '09:00:00', 10);
        $this->createReservation($today->format('Y-m-d'), $today->copy()->addDays(2)->format('Y-m-d'), '11:00:00', 100);
        $this->createReservation($today->copy()->subDays(1)->format('Y-m-d'), $today->copy()->addDays(1)->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->addDays(1)->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '10:00:00', 1);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('GET', route('reservation.checkin.list', ['date' => $today->copy()->addDays(1)->format('Y-m-d')]));
        $response->assertStatus(200);

        $json = json_decode($response->content(), true);
        $this->assertCount(1, $json);
        $this->assertEquals($today->addDays(1)->format('Y-m-d 00:00:00'), $json[0]['start_date']);
    }

    public function testCheckoutList1()
    {
        $today = Carbon::today();
        $this->createReservation($today->copy()->subDays(1)->format('Y-m-d'), $today->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->subDays(3)->format('Y-m-d'), $today->format('Y-m-d'), '09:00:00', 10);
        $this->createReservation($today->copy()->subDays(2)->format('Y-m-d'), $today->format('Y-m-d'), '11:00:00', 100);
        $this->createReservation($today->copy()->subDays(3)->format('Y-m-d'), $today->copy()->subDays(1)->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->addDays(1)->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '10:00:00', 1);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('GET', route('reservation.checkout.list'));
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

    public function testCheckoutList2()
    {
        $today = Carbon::today();
        $this->createReservation($today->copy()->subDays(1)->format('Y-m-d'), $today->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->subDays(3)->format('Y-m-d'), $today->format('Y-m-d'), '09:00:00', 10);
        $this->createReservation($today->copy()->subDays(2)->format('Y-m-d'), $today->format('Y-m-d'), '11:00:00', 100);
        $this->createReservation($today->copy()->subDays(3)->format('Y-m-d'), $today->copy()->subDays(1)->format('Y-m-d'), '10:00:00', 1);
        $this->createReservation($today->copy()->addDays(1)->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '10:00:00', 1);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('GET', route('reservation.checkout.list', ['date' => $today->copy()->addDays(3)->format('Y-m-d')]));
        $response->assertStatus(200);

        $json = json_decode($response->content(), true);
        $this->assertCount(1, $json);
        $this->assertEquals($today->addDays(3)->format('Y-m-d 00:00:00'), $json[0]['end_date']);
        $this->assertEquals('10:00:00', $json[0]['checkout']);
    }

    public function testCheckin()
    {
        $today       = Carbon::today();
        $reservation = $this->createReservation($today->copy()->addDay()->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '10:00:00', 1);
        $this->assertEquals('reserved', Reservation::find($reservation->id)->status);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('PATCH', route('reservation.checkin', [
            'id' => $reservation->id,
        ]));
        $response->assertStatus(200)->assertExactJson(['result' => true]);

        $this->assertEquals('checkin', Reservation::find($reservation->id)->status);
    }

    public function testCheckout1()
    {
        $today       = Carbon::today();
        $reservation = $this->createReservation($today->copy()->addDay()->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '10:00:00', 1);
        $this->assertEquals('reserved', Reservation::find($reservation->id)->status);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('PATCH', route('reservation.checkout', [
            'id' => $reservation->id,
        ]));
        $response->assertStatus(200)->assertExactJson(['result' => true]);

        $this->assertEquals('checkout', Reservation::find($reservation->id)->status);
        $this->assertEquals(1 * 3, Reservation::find($reservation->id)->payment);
    }

    public function testCheckout2()
    {
        $today       = Carbon::today();
        $reservation = $this->createReservation($today->copy()->addDay()->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '10:00:00', 1);
        $this->assertEquals('reserved', Reservation::find($reservation->id)->status);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('PATCH', route('reservation.checkout', [
            'id'      => $reservation->id,
            'payment' => 100,
        ]));
        $response->assertStatus(200)->assertExactJson(['result' => true]);

        $this->assertEquals('checkout', Reservation::find($reservation->id)->status);
        $this->assertEquals(100, Reservation::find($reservation->id)->payment);
    }

    public function testCancel()
    {
        $today       = Carbon::today();
        $reservation = $this->createReservation($today->copy()->addDay()->format('Y-m-d'), $today->copy()->addDays(3)->format('Y-m-d'), '10:00:00', 1);
        $this->assertEquals('reserved', Reservation::find($reservation->id)->status);

        $admin    = factory(Admin::class)->create();
        $response = $this->actingAs($admin)->json('PATCH', route('reservation.cancel', [
            'id' => $reservation->id,
        ]));
        $response->assertStatus(200)->assertExactJson(['result' => true]);

        $this->assertEquals('canceled', Reservation::find($reservation->id)->status);
    }
}
