<?php
declare(strict_types=1);

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use Tests\TestHelper;
use Tests\Unit\Helpers\Implement\TimeHelperImplemented;

/**
 * Class TimeHelperTest
 * @package Tests\Unit\Models
 * @group Unit
 * @group Unit.Helpers
 * @SuppressWarnings(PMD.TooManyPublicMethods)
 */
class TimeHelperTest extends TestCase
{
    use TestHelper;

    /** @var TimeHelperImplemented $helper */
    private static $helper;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        require_once __DIR__.'/Implement/TimeHelperImplemented.php';
        self::$helper = new TimeHelperImplemented();
    }

    public function setUp(): void
    {
        parent::setUp();
        static::truncateTables();
        static::runSeed([
            '--class' => 'SettingTableSeeder',
        ]);
    }

    public function testGetCheckInTime()
    {
        $this->assertEquals('15:00', self::$helper->getCheckInTime());
    }

    public function testGetCheckOutTime()
    {
        $this->assertEquals('10:00', self::$helper->getCheckOutTime());
    }

    public function testIsBeforeCheckIn()
    {
        self::$helper->setNow(today()->setTime(14, 59, 59)->timestamp);
        $this->assertTrue(self::$helper->isBeforeCheckIn());

        self::$helper->setNow(today()->setTime(15, 0, 0)->timestamp);
        $this->assertFalse(self::$helper->isBeforeCheckIn());
    }

    public function testIsBeforeCheckOut()
    {
        self::$helper->setNow(today()->setTime(9, 59, 59)->timestamp);
        $this->assertTrue(self::$helper->isBeforeCheckOut());

        self::$helper->setNow(today()->setTime(10, 0, 0)->timestamp);
        $this->assertFalse(self::$helper->isBeforeCheckOut());
    }

    public function testToday()
    {
        self::$helper->setNow(today()->setTime(10, 0, 0)->timestamp);
        $this->assertEquals(today()->year, self::$helper->today()->year);
        $this->assertEquals(today()->month, self::$helper->today()->month);
        $this->assertEquals(today()->day, self::$helper->today()->day);
        $this->assertEquals(0, self::$helper->today()->hour);
        $this->assertEquals(0, self::$helper->today()->minute);
        $this->assertEquals(0, self::$helper->today()->second);
        $this->assertEquals(0, self::$helper->today()->microsecond);
    }

    public function testYesterday()
    {
        self::$helper->setNow(today()->setTime(10, 0, 0)->timestamp);
        $this->assertEquals(today()->subDay()->year, self::$helper->yesterday()->year);
        $this->assertEquals(today()->subDay()->month, self::$helper->yesterday()->month);
        $this->assertEquals(today()->subDay()->day, self::$helper->yesterday()->day);
        $this->assertEquals(0, self::$helper->yesterday()->hour);
        $this->assertEquals(0, self::$helper->yesterday()->minute);
        $this->assertEquals(0, self::$helper->yesterday()->second);
        $this->assertEquals(0, self::$helper->yesterday()->microsecond);
    }

    public function testTomorrow()
    {
        self::$helper->setNow(today()->setTime(10, 0, 0)->timestamp);
        $this->assertEquals(today()->addDay()->year, self::$helper->tomorrow()->year);
        $this->assertEquals(today()->addDay()->month, self::$helper->tomorrow()->month);
        $this->assertEquals(today()->addDay()->day, self::$helper->tomorrow()->day);
        $this->assertEquals(0, self::$helper->tomorrow()->hour);
        $this->assertEquals(0, self::$helper->tomorrow()->minute);
        $this->assertEquals(0, self::$helper->tomorrow()->second);
        $this->assertEquals(0, self::$helper->tomorrow()->microsecond);
    }
}
