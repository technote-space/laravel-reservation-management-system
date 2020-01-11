<?php
declare(strict_types=1);

namespace Tests\Unit\Helpers;

use Exception;
use Tests\TestCase;
use Tests\Unit\Helpers\Implement\ValueHelperImplemented;

/**
 * Class ValueHelperTest
 * @package Tests\Unit\Models
 * @group Unit
 * @group Unit.Helpers
 * @SuppressWarnings(PMD.TooManyPublicMethods)
 */
class ValueHelperTest extends TestCase
{
    /** @var ValueHelperImplemented $helper */
    private static $helper;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        require_once __DIR__.'/Implement/ValueHelperImplemented.php';
        self::$helper = new ValueHelperImplemented();
    }

    /**
     * @dataProvider getWeekTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $time
     *
     * @throws Exception
     */
    public function testGetWeek(string $expected, string $time)
    {
        $this->assertEquals($expected, self::$helper->getWeek($time));
    }

    public function getWeekTestDataProvider()
    {
        return [
            ['(月)', '2019-07-1'],
            ['(火)', '2019-7-2'],
            ['(水)', '2019-07-03'],
            ['(木)', '2019-07-04 12:00'],
            ['(金)', '2019-07-05 15:00:00'],
            ['(土)', '2019-07-06'],
            ['(日)', '2019-07-07'],
            ['(月)', '2019-07-08'],
        ];
    }

    /**
     * @dataProvider getDateTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $time
     *
     * @throws Exception
     */
    public function testGetDate(string $expected, string $time)
    {
        $this->assertTrue(1 === preg_match("/\A{$expected}\z/", self::$helper->getDate($time)));
    }

    public function getDateTestDataProvider()
    {
        $year1 = date('Y');
        $year2 = date('Y', strtotime('+1 year'));
        $year3 = date('Y', strtotime('-1 year'));
        $week  = '(日|月|火|水|木|金|土)';

        return [
            ["7月1日\({$week}\)", "{$year1}-07-1"],
            ["7月2日\({$week}\)", "{$year1}-7-2"],
            ["7月3日\({$week}\)", "{$year1}-07-03"],
            ["7月4日\({$week}\)", "{$year1}-07-04 12:00"],
            ["7月5日\({$week}\)", "{$year1}-07-05 15:00:00"],
            ["{$year2}年7月6日\({$week}\)", "{$year2}-07-06"],
            ["{$year3}年7月6日\({$week}\)", "{$year3}-07-06"],
        ];
    }

    /**
     * @dataProvider toHalfTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $string
     */
    public function testToHalf(string $expected, string $string)
    {
        $this->assertEquals($expected, self::$helper->toHalf($string));
    }

    public function toHalfTestDataProvider()
    {
        return [
            ['123', '１２３'],
            ['xyz', 'ｘｙｚ'],
        ];
    }

    /**
     * @dataProvider toFullTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $string
     */
    public function testToFull(string $expected, string $string)
    {
        $this->assertEquals($expected, self::$helper->toFull($string));
    }

    public function toFullTestDataProvider()
    {
        return [
            ['１２３', '123'],
            ['ｘｙｚ', 'xyz'],
        ];
    }

    /**
     * @dataProvider toHalfSpaceTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $string
     */
    public function testToHalfSpace(string $expected, string $string)
    {
        $this->assertEquals($expected, self::$helper->toHalfSpace($string));
    }

    public function toHalfSpaceTestDataProvider()
    {
        return [
            ['1 2 3', '1　2 3'],
            ['x y z ', 'x y　z　'],
        ];
    }

    /**
     * @dataProvider toFullSpaceTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $string
     */
    public function testToFullSpace(string $expected, string $string)
    {
        $this->assertEquals($expected, self::$helper->toFullSpace($string));
    }

    public function toFullSpaceTestDataProvider()
    {
        return [
            ['1　2　3', '1 2　3'],
            ['x　y　z　', 'x　y z '],
        ];
    }

    /**
     * @dataProvider toHalfDashTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $string
     */
    public function testToHalfDash(string $expected, string $string)
    {
        $this->assertEquals($expected, self::$helper->toHalfDash($string));
    }

    public function toHalfDashTestDataProvider()
    {
        return [
            ['1-2-3', '1-2－3'],
            ['x-y-z', 'x―y‐z'],
        ];
    }

    /**
     * @dataProvider toFullDashTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $string
     */
    public function testToFullDash(string $expected, string $string)
    {
        $this->assertEquals($expected, self::$helper->toFullDash($string));
    }

    public function toFullDashTestDataProvider()
    {
        return [
            ['1－2－3', '1-2-3'],
            ['x－y－z', 'x-y-z'],
        ];
    }

    /**
     * @dataProvider escapeTextTestDataProvider
     *
     * @param  string  $expected
     * @param  string  $string
     */
    public function testEscapeText(string $expected, string $string)
    {
        $this->assertEquals($expected, self::$helper->escapeText($string));
    }

    public function escapeTextTestDataProvider()
    {
        return [
            ['', ''],
            ['abcd e', "a\r\nb\rc\nd,e"],
        ];
    }
}
