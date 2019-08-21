<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Guest;
use App\Models\GuestDetail;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\Room;

/**
 * Class ReservationDetailTest
 * @package Tests\Unit\Models
 * @group Unit
 * @group Unit.Models
 */
class ReservationDetailTest extends BaseTestCase
{
    protected function getTarget(): string
    {
        return 'detail';
    }

    /** @var ReservationDetail $detail */
    protected static $detail;

    /** @var Reservation $reservation */
    protected static $reservation;

    protected static function seeder(): void
    {
        $guest = factory(Guest::class)->create();
        factory(GuestDetail::class)->create([
            'guest_id' => $guest->id,
        ]);
        self::$reservation = factory(Reservation::class)->create([
            'guest_id' => $guest->id,
            'room_id'  => factory(Room::class)->create()->id,
        ]);
        self::$detail      = factory(ReservationDetail::class)->create([
            'reservation_id' => self::$reservation->id,
        ]);
    }

    public function belongToDataProvider(): array
    {
        return [
            [Reservation::class, 'reservation'],
        ];
    }
}
