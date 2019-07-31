<?php
declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * App\Models\Reservation
 *
 * @property int $id
 * @property int $guest_id 利用者ID
 * @property int $room_id 部屋ID
 * @property Carbon $start_date 利用開始日
 * @property Carbon $end_date 利用終了日
 * @property int $number 利用人数
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Guest $guest
 * @property-read Room $room
 * @method static Builder|Reservation newModelQuery()
 * @method static Builder|Reservation newQuery()
 * @method static Builder|Reservation query()
 * @method static Builder|Reservation whereCreatedAt($value)
 * @method static Builder|Reservation whereEndDate($value)
 * @method static Builder|Reservation whereGuestId($value)
 * @method static Builder|Reservation whereId($value)
 * @method static Builder|Reservation whereNumber($value)
 * @method static Builder|Reservation whereRoomId($value)
 * @method static Builder|Reservation whereStartDate($value)
 * @method static Builder|Reservation whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read string $end_datetime
 * @property-read bool $is_future
 * @property-read bool $is_past
 * @property-read bool $is_present
 * @property-read string $start_datetime
 */
class Reservation extends Model
{
    use HasEagerLimit;

    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'guest_id' => 'int',
        'room_id'  => 'int',
        'number'   => 'int',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'start_datetime',
        'end_datetime',
        'is_past',
        'is_present',
        'is_future',
    ];

    /** @var int $now */
    private static $now;

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * @return string
     */
    public function getStartDatetimeAttribute(): string
    {
        return $this->start_date.' '.Setting::getSetting('check_in');
    }

    /**
     * @return string
     */
    public function getEndDatetimeAttribute(): string
    {
        return Carbon::parse($this->end_date.' '.Setting::getSetting('check_out'))->addDay()->format('Y-m-d H:i');
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsPastAttribute(): bool
    {
        return strtotime($this->end_datetime) < $this->now();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsPresentAttribute(): bool
    {
        return strtotime($this->start_datetime) <= $this->now() && strtotime($this->end_datetime) >= $this->now();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsFutureAttribute(): bool
    {
        return strtotime($this->start_datetime) > $this->now();
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
     * @param  int|null  $now
     */
    public static function setNow(?int $now)
    {
        static::$now = $now;
    }
}
