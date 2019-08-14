<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class RoomTest
 * @package Tests\Unit\Models
 * @group Unit
 * @group Unit.Models
 */
class RoomTest extends BaseTestCase
{
    protected function getTarget(): string
    {
        return 'room';
    }

    /** @var Guest $guest */
    protected static $guest;

    /** @var Room $room */
    protected static $room;

    /** @var Reservation[] $reservations */
    protected static $reservations;

    protected static function seeder(): void
    {
        self::$guest        = factory(Guest::class)->create();
        self::$room         = factory(Room::class)->create();
        self::$reservations = factory(Reservation::class, 5)->create([
            'guest_id' => self::$guest->id,
            'room_id'  => self::$room->id,
        ]);
    }

    public function hasManyDataProvider(): array
    {
        return [
            [Reservation::class, 'reservations', 5],
        ];
    }

    public function testCheckReserved()
    {
        Reservation::all()->each(function ($row) {
            /** @var Model $row */
            $row->delete();
        });
        $room  = factory(Room::class)->create();
        $guest = factory(Guest::class)->create();
        factory(Reservation::class)->create([
            'guest_id'   => $guest->id,
            'room_id'    => $room->id,
            'start_date' => date('Y-m-d'),
            'end_date'   => now()->addDays(2)->format('Y-m-d'),
            'number'     => 1,
        ]);
        static::runSeed([
            '--class' => 'SettingTableSeeder',
        ]);

        Reservation::setNow(strtotime(date('Y-m-d 14:59:59')));
        $this->assertFalse(Room::find($room->id)->is_reserved);

        Reservation::setNow(strtotime(date('Y-m-d 15:00:00')));
        $this->assertTrue(Room::find($room->id)->is_reserved);

        Reservation::setNow(strtotime(date('Y-m-d 10:00:00').' +1days'));
        $this->assertTrue(Room::find($room->id)->is_reserved);

        Reservation::setNow(strtotime(date('Y-m-d 10:00:00').' +2days'));
        $this->assertTrue(Room::find($room->id)->is_reserved);

        Reservation::setNow(strtotime(date('Y-m-d 10:00:00').' +3days'));
        $this->assertTrue(Room::find($room->id)->is_reserved);

        Reservation::setNow(strtotime(date('Y-m-d 10:00:01').' +3days'));
        $this->assertFalse(Room::find($room->id)->is_reserved);

        Reservation::setNow(null);
    }

    public function testGetLatestReservation()
    {
        $this->assertGreaterThanOrEqual(5, Reservation::count());
        $this->assertEquals(self::$room->latestReservation->id, Reservation::latest('start_date')->first()->id);
    }

    public function testGetRecentUsages()
    {
        Room::setNow(strtotime(date('Y-m-d 15:00:00')));
        static::runSeed([
            '--class' => 'SettingTableSeeder',
        ]);
        Reservation::all()->each(function ($row) {
            /** @var Model $row */
            $row->delete();
        });
        factory(Reservation::class, 10)->create([
            'guest_id'   => self::$guest->id,
            'room_id'    => self::$room->id,
            'start_date' => Carbon::tomorrow()->format('Y-m-d'),
        ]);
        $this->assertEquals(10, Room::find(self::$room->id)->reservations->count());
        $this->assertEquals(0, Room::find(self::$room->id)->recentUsages->count());
        $this->assertEmpty(Room::find(self::$room->id)->latestUsage);

        factory(Reservation::class, 10)->create([
            'guest_id'   => self::$guest->id,
            'room_id'    => self::$room->id,
            'start_date' => Carbon::yesterday()->format('Y-m-d'),
        ]);
        $this->assertEquals(20, Room::find(self::$room->id)->reservations->count());
        $this->assertEquals(5, Room::find(self::$room->id)->recentUsages->count());
        $this->assertNotEmpty(Room::find(self::$room->id)->latestUsage);

        Room::setNow(null);
    }
}
