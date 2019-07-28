<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;

/**
 * Class RoomTest
 * @package Tests\Unit\Models
 * @group Models
 */
class RoomTest extends BaseTestCase
{
    protected function getTarget(): string
    {
        return 'room';
    }

    /** @var Room $room */
    protected static $room;

    /** @var Reservation[] $reservations */
    protected static $reservations;

    protected static function seeder()
    {
        $guest              = factory(Guest::class)->create();
        self::$room         = factory(Room::class)->create();
        self::$reservations = factory(Reservation::class, 2)->create([
            'guest_id' => $guest->id,
            'room_id'  => self::$room->id,
        ]);
    }

    public function hasManyDataProvider(): array
    {
        return [
            [Reservation::class, 'reservations'],
        ];
    }
}
