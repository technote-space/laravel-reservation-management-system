<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Setting;

/**
 * Class ReservationTest
 * @package Tests\Unit\Models
 * @group Unit
 * @group Unit.Models
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

    protected static function seeder(): void
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

    public function testAttributes()
    {
        $reservation = factory(Reservation::class)->create([
            'guest_id'   => self::$guest->id,
            'room_id'    => self::$room->id,
            'start_date' => date('Y-m-d'),
            'end_date'   => date('Y-m-d'),
        ]);
        $this->runSeed([
            '--class' => 'SettingTableSeeder',
        ]);
        Setting::clearCache();

        Reservation::setNow(strtotime(date('Y-m-d 14:59:59')));
        $this->assertFalse(Reservation::find($reservation->id)->is_past);
        $this->assertFalse(Reservation::find($reservation->id)->is_present);
        $this->assertTrue(Reservation::find($reservation->id)->is_future);

        Reservation::setNow(strtotime(date('Y-m-d 15:00:00')));
        $this->assertFalse(Reservation::find($reservation->id)->is_past);
        $this->assertTrue(Reservation::find($reservation->id)->is_present);
        $this->assertFalse(Reservation::find($reservation->id)->is_future);

        Reservation::setNow(strtotime(date('Y-m-d 10:00:00').' +1days'));
        $this->assertFalse(Reservation::find($reservation->id)->is_past);
        $this->assertTrue(Reservation::find($reservation->id)->is_present);
        $this->assertFalse(Reservation::find($reservation->id)->is_future);

        Reservation::setNow(strtotime(date('Y-m-d 10:00:01').' +1days'));
        $this->assertTrue(Reservation::find($reservation->id)->is_past);
        $this->assertFalse(Reservation::find($reservation->id)->is_present);
        $this->assertFalse(Reservation::find($reservation->id)->is_future);

        Reservation::setNow(null);
    }
}
