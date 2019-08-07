<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Guest;
use App\Models\GuestDetail;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class GuestTest
 * @package Tests\Unit\Models
 * @group Unit
 * @group Unit.Models
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
        self::$reservations = factory(Reservation::class, 5)->create([
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
            [Reservation::class, 'reservations', 5],
        ];
    }

    public function testGetLatestReservation()
    {
        $this->assertGreaterThanOrEqual(5, Reservation::count());
        $this->assertEquals(self::$guest->latestReservation->id, Reservation::latest('start_date')->first()->id);
    }

    public function testGetRecentUsages()
    {
        Guest::setNow(strtotime(date('Y-m-d 15:00:00')));
        static::runSeed([
            '--class' => 'SettingTableSeeder',
        ]);
        Setting::clearCache();
        Reservation::all()->each(function ($row) {
            /** @var Model $row */
            $row->delete();
        });
        factory(Reservation::class, 10)->create([
            'guest_id'   => self::$guest->id,
            'room_id'    => self::$guest->id,
            'start_date' => Carbon::tomorrow()->format('Y-m-d'),
        ]);
        $this->assertEquals(10, Guest::find(self::$guest->id)->reservations->count());
        $this->assertEquals(0, Guest::find(self::$guest->id)->recentUsages->count());
        $this->assertEmpty(Guest::find(self::$guest->id)->latestUsage);

        factory(Reservation::class, 10)->create([
            'guest_id'   => self::$guest->id,
            'room_id'    => self::$guest->id,
            'start_date' => Carbon::yesterday()->format('Y-m-d'),
        ]);
        $this->assertEquals(20, Guest::find(self::$guest->id)->reservations->count());
        $this->assertEquals(5, Guest::find(self::$guest->id)->recentUsages->count());
        $this->assertNotEmpty(Guest::find(self::$guest->id)->latestUsage);

        Guest::setNow(null);
    }
}
