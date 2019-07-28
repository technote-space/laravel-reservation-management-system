<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;

/**
 * Class ReservationTest
 * @package Tests\Unit\Models
 * @group Models
 */
class ReservationTest extends BaseTestCase
{
    protected function getTarget(): string
    {
        return 'reservation';
    }

    /** @var Reservation $reservation */
    protected static $reservation;

    /** @var Guest $guest */
    protected static $guest;

    /** @var Room $room */
    protected static $room;

    protected static function seeder()
    {
        self::$guest       = factory(Guest::class)->create();
        self::$room        = factory(Room::class)->create();
        self::$reservation = factory(Reservation::class)->create([
            'guest_id' => self::$guest->id,
            'room_id'  => self::$room->id,
        ]);
    }

    public function belongToDataProvider(): array
    {
        return [
            [Guest::class, 'guest'],
            [Room::class, 'room'],
        ];
    }
}
