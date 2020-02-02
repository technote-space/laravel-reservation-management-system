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
    protected function getCheckinTime(): string
    {
        return Setting::getSetting('checkin');
    }

    /**
     * @param  string  $date
     *
     * @return Carbon
     */
    protected function getCheckinDatetime(string $date): Carbon
    {
        return Carbon::parse($date.' '.$this->getCheckinTime());
    }

    /**
     * @param  string  $date
     * @param  string  $checkoutTime
     *
     * @return Carbon
     */
    protected function getCheckoutDatetime(string $date, string $checkoutTime): Carbon
    {
        return Carbon::parse($date.' '.$checkoutTime);
    }

    /**
     * @return bool
     */
    protected function isBeforeCheckin(): bool
    {
        return $this->now() < $this->getCheckinDatetime($this->today()->format('Y-m-d'))->timestamp;
    }

    /**
     * @param  string  $checkoutTime
     *
     * @return bool
     */
    protected function isBeforeCheckout(string $checkoutTime): bool
    {
        return $this->now() < $this->getCheckoutDatetime($this->today()->format('Y-m-d'), $checkoutTime)->timestamp;
    }

    /**
     * @return \Carbon\Carbon|Carbon
     */
    protected function getCheckinThresholdDay()
    {
        return $this->isBeforeCheckin() ? $this->yesterday() : $this->today();
    }

    /**
     * @return int
     */
    protected function now(): int
    {
        if (! isset(static::$now)) {
            static::$now = time();
        }

        return static::$now;
    }

    /**
     * @return Carbon
     */
    protected function today()
    {
        return Carbon::createFromTimestamp($this->now())->setTime(0, 0);
    }

    /**
     * @return \Carbon\Carbon|Carbon
     */
    protected function yesterday()
    {
        return $this->today()->subDay();
    }

    /**
     * @return \Carbon\Carbon|Carbon
     */
    protected function tomorrow()
    {
        return $this->today()->addDay();
    }

    /**
     * @param  int|null  $now
     */
    public static function setNow(?int $now)
    {
        static::$now = $now;
    }
}
