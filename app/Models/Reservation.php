<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\Traits\TimeHelper;
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
 * @property-read bool $is_future
 * @property-read bool $is_past
 * @property-read bool $is_present
 * @property-read Carbon $start_datetime
 * @property-read Carbon $end_datetime
 * @property-read string $end_date_str
 * @property-read string $start_date_str
 */
class Reservation extends Model
{
    use HasEagerLimit, TimeHelper;

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
        'guest_id'   => 'int',
        'room_id'    => 'int',
        'start_date' => 'date',
        'end_date'   => 'date',
        'number'     => 'int',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'start_date_str',
        'end_date_str',
        'start_datetime',
        'end_datetime',
        'is_past',
        'is_present',
        'is_future',
    ];

    /**
     * @return BelongsTo
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * @return string
     */
    public function getStartDateStrAttribute(): string
    {
        return $this->start_date->format('Y-m-d');
    }

    /**
     * @return string
     */
    public function getEndDateStrAttribute(): string
    {
        return $this->end_date->format('Y-m-d');
    }

    /**
     * @return Carbon
     */
    public function getStartDatetimeAttribute(): Carbon
    {
        return $this->getCheckInDatetime($this->start_date->format('Y-m-d'));
    }

    /**
     * @return Carbon
     */
    public function getEndDatetimeAttribute(): Carbon
    {
        return $this->getCheckOutDatetime($this->end_date->format('Y-m-d'))->addDay();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsPastAttribute(): bool
    {
        return $this->end_datetime->timestamp < $this->now();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsPresentAttribute(): bool
    {
        return $this->start_datetime->timestamp <= $this->now() && $this->end_datetime->timestamp >= $this->now();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsFutureAttribute(): bool
    {
        return $this->start_datetime->timestamp > $this->now();
    }
}
