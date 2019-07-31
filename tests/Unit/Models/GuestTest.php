<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Guest;
use App\Models\GuestDetail;
use App\Models\Reservation;
use App\Models\Room;

/**
 * Class GuestTest
 * @package Tests\Unit\Models
 * @group Models
 */
class GuestTest extends BaseTestCase
{
    protected function getTarget(): string
    {
        return 'guest';
    }

    /** @var Guest $guest */
    protected static $guest;

    /** @var GuestDetail $detail */
    protected static $detail;

    /** @var Reservation[] $reservations */
    protected static $reservations;

    protected static function seeder(): void
    {
        self::$guest        = factory(Guest::class)->create();
        self::$detail       = factory(GuestDetail::class)->create([
            'guest_id' => self::$guest->id,
        ]);
        $room               = factory(Room::class)->create();
        self::$reservations = factory(Reservation::class, 2)->create([
            'guest_id' => self::$guest->id,
            'room_id'  => $room->id,
        ]);
    }

    public function hasOneDataProvider(): array
    {
        return [
            [GuestDetail::class, 'detail'],
        ];
    }

    public function hasManyDataProvider(): array
    {
        return [
            [Reservation::class, 'reservations'],
        ];
    }
}
