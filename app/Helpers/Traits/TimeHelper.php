<?php
declare(strict_types=1);

namespace App\Helpers\Traits;

use App\Models\Setting;
use Illuminate\Support\Carbon;

/**
 * Trait TimeHelper
 * @package App\Helpers\Traits
 */
trait TimeHelper
{
    /**
     * @var int $now
     */
    private static $now;

    /**
     * @return string
     */
    protected static function getCheckinTime(): string
    {
        return Setting::getSetting('checkin') ?? "";
    }

    /**
     * @param  string  $date
     *
     * @return Carbon
     */
    protected static function getCheckinDatetime(string $date): Carbon
    {
        return Carbon::parse($date.' '.static::getCheckinTime());
    }

    /**
     * @param  string  $date
     * @param  string  $checkoutTime
     *
     * @return Carbon
     */
    protected static function getCheckoutDatetime(string $date, string $checkoutTime): Carbon
    {
        return Carbon::parse($date.' '.$checkoutTime);
    }

    /**
     * @return bool
     */
    protected static function isBeforeCheckin(): bool
    {
        return static::now() < static::getCheckinDatetime(static::today()->format('Y-m-d'))->timestamp;
    }

    /**
     * @param  string  $checkoutTime
     *
     * @return bool
     */
    protected static function isBeforeCheckout(string $checkoutTime): bool
    {
        return static::now() < static::getCheckoutDatetime(static::today()->format('Y-m-d'), $checkoutTime)->timestamp;
    }

    /**
     * @return \Carbon\Carbon|Carbon
     */
    protected static function getCheckinThresholdDay()
    {
        return static::isBeforeCheckin() ? static::yesterday() : static::today();
    }

    /**
     * @return int
     */
    protected static function now(): int
    {
        if (! isset(static::$now)) {
            static::$now = time();
        }

        return static::$now;
    }

    /**
     * @return Carbon
     */
    protected static function today()
    {
        return Carbon::createFromTimestamp(self::now())->setTime(0, 0);
    }

    /**
     * @return \Carbon\Carbon|Carbon
     */
    protected static function yesterday()
    {
        return static::today()->subDay();
    }

    /**
     * @return \Carbon\Carbon|Carbon
     */
    protected static function tomorrow()
    {
        return static::today()->addDay();
    }

    /**
     * @param  int|null  $now
     */
    public static function setNow(?int $now)
    {
        static::$now = $now;
    }
}
