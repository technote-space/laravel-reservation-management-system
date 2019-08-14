<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\Traits\TimeHelper;
use App\Models\Traits\Searchable;
use App\Models\Contracts\Searchable as SearchableContract;
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
 * @property-read bool $is_future
 * @property-read bool $is_past
 * @property-read bool $is_present
 * @property-read Carbon $start_datetime
 * @property-read Carbon $end_datetime
 * @property-read string $end_date_str
 * @property-read string $start_date_str
 * @mixin Eloquent
 * @mixin Builder
 */
class Reservation extends Model implements SearchableContract
{
    use HasEagerLimit, TimeHelper, Searchable;

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
     * @var int
     */
    protected $perPage = 10;

    /**
     * @param  Builder  $query
     * @param  array  $conditions
     */
    protected function setConditions(Builder $query, array $conditions)
    {
        if (! empty($conditions['s'])) {
            collect($conditions['s'])->each(function ($search) use ($query) {
                $query->where(function ($builder) use ($search) {
                    /** @var Builder $builder */
                    $builder->where('guest_details.name', 'like', "%{$search}%")
                            ->orWhere('guest_details.name_kana', 'like', "%{$search}%")
                            ->orWhere('guest_details.address', 'like', "%{$search}%")
                            ->orWhere('rooms.name', 'like', "%{$search}%");
                });
            });
        }

        if (! empty($conditions['start_date'])) {
            $query->whereDate('reservations.end_date', '>=', $conditions['start_date']);
        }
        if (! empty($conditions['end_date'])) {
            $query->whereDate('reservations.start_date', '<', $conditions['end_date']);
        }

        if (! empty($conditions['room_id'])) {
            $query->where('reservations.room_id', $conditions['room_id']);
        }
    }

    /**
     * @return array
     */
    protected function getJoinData(): array
    {
        return [
            'rooms'         => [
                'first'  => 'rooms.id',
                'second' => 'reservations.room_id',
            ],
            'guests'        => [
                'first'  => 'guests.id',
                'second' => 'reservations.guest_id',
            ],
            'guest_details' => [
                'first'  => 'guest_details.guest_id',
                'second' => 'guests.id',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getOrderBy(): array
    {
        return [
            'reservations.start_date' => 'desc',
            'reservations.id'         => 'desc',
        ];
    }

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

    /**
     * @param  string|null  $startDate
     * @param  string|null  $endDate
     *
     * @return bool
     */
    public static function isTermValid(?string $startDate, ?string $endDate): bool
    {
        if (empty($startDate) || empty($endDate)) {
            return true;
        }

        if (strtotime($startDate) > strtotime($endDate)) {
            return false;
        }

        if (Setting::getSetting('max_day') <= Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate))) {
            return false;
        }

        return true;
    }

    /**
     * @param  int|null  $reservationId
     * @param  int|null  $roomId
     * @param  string|null  $startDate
     * @param  string|null  $endDate
     *
     * @return bool
     */
    public static function isReservationAvailable(?int $reservationId, ?int $roomId, ?string $startDate, ?string $endDate): bool
    {
        if (empty($roomId) || empty($startDate) || empty($endDate)) {
            return true;
        }

        $startDate = self::normalizeDate($startDate);
        $endDate   = self::normalizeDate($endDate);

        $builder = static::where('room_id', $roomId)
                         ->where('end_date', '>=', $startDate)
                         ->where('start_date', '<=', $endDate);
        if (! empty($reservationId)) {
            $builder->where('id', '!=', $reservationId);
        }

        return ! $builder->exists();
    }

    /**
     * @param  int|null  $reservationId
     * @param  int|null  $guestId
     * @param  string|null  $startDate
     * @param  string|null  $endDate
     *
     * @return bool
     */
    public static function isNotDuplicated(?int $reservationId, ?int $guestId, ?string $startDate, ?string $endDate): bool
    {
        if (empty($guestId) || empty($startDate) || empty($endDate)) {
            return true;
        }

        $startDate = self::normalizeDate($startDate);
        $endDate   = self::normalizeDate($endDate);

        $builder = static::where('guest_id', $guestId)
                         ->where('end_date', '>=', $startDate)
                         ->where('start_date', '<=', $endDate);
        if (! empty($reservationId)) {
            $builder->where('id', '!=', $reservationId);
        }

        return ! $builder->exists();
    }

    /**
     * @param  string  $date
     *
     * @return string
     */
    // @codeCoverageIgnoreStart
    private static function normalizeDate(string $date)
    {
        if ('sqlite' === config('database.default')) {
            if (strlen($date) <= 10) {
                return $date.' 00:00:00';
            }
        }

        return $date;
    }
    // @codeCoverageIgnoreEnd
}
