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
    protected function getCheckInTime(): string
    {
        return Setting::getSetting('check_in');
    }

    /**
     * @return string
     */
    protected function getCheckOutTime(): string
    {
        return Setting::getSetting('check_out');
    }

    /**
     * @param  string  $date
     *
     * @return Carbon
     */
    protected function getCheckInDatetime(string $date): Carbon
    {
        return Carbon::parse($date.' '.$this->getCheckInTime());
    }

    /**
     * @param  string  $date
     *
     * @return Carbon
     */
    protected function getCheckOutDatetime(string $date): Carbon
    {
        return Carbon::parse($date.' '.$this->getCheckOutTime());
    }

    /**
     * @return bool
     */
    protected function isBeforeCheckIn(): bool
    {
        return $this->now() < $this->getCheckInDatetime($this->today()->format('Y-m-d'))->timestamp;
    }

    /**
     * @return bool
     */
    protected function isBeforeCheckOut(): bool
    {
        return $this->now() < $this->getCheckOutDatetime($this->today()->format('Y-m-d'))->timestamp;
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
        return Carbon::createFromTimestamp($this->now());
    }

    /**
     * @return Carbon
     */
    protected function yesterday()
    {
        return $this->today()->subDay();
    }

    /**
     * @return Carbon
     */
    protected function getCheckInThresholdDay()
    {
        return $this->isBeforeCheckIn() ? $this->yesterday() : $this->today();
    }

    /**
     * @return Carbon
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