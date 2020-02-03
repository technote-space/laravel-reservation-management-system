<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\Traits\TimeHelper;
use Doctrine\DBAL\Schema\Column;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Technote\CrudHelper\Models\Contracts\Crudable as CrudableContract;
use Technote\CrudHelper\Models\Traits\Crudable;
use Technote\SearchHelper\Models\Contracts\Searchable as SearchableContract;
use Technote\SearchHelper\Models\Traits\Searchable;

/**
 * App\Models\Reservation
 *
 * @property int $id
 * @property int $guest_id 利用者ID
 * @property int $room_id 部屋ID
 * @property Carbon $start_date 利用開始日
 * @property Carbon $end_date 利用終了日
 * @property string $checkout チェックアウト時間
 * @property string $status ステータス
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
 * @method static Builder|Reservation whereCheckout($value)
 * @method static Builder|Reservation whereStatus($value)
 * @property-read bool $is_future
 * @property-read bool $is_past
 * @property-read bool $is_present
 * @property-read Carbon $start_datetime
 * @property-read Carbon $end_datetime
 * @property-read string $end_date_str
 * @property-read string $start_date_str
 * @property-read ReservationDetail $detail
 * @property-read int $charge
 * @property-read int $days
 * @property-read int $nights
 * @property-read string $stays
 * @property-read Guest $guest
 * @property-read Room $room
 * @property-read int $payment
 * @mixin Eloquent
 * @mixin Builder
 */
class Reservation extends Model implements CrudableContract, SearchableContract
{
    use HasEagerLimit, TimeHelper, Crudable, Searchable;

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
        'days',
        'nights',
        'stays',
        'charge',
    ];

    /**
     * @var array
     */
    protected $with = [
        'detail',
        'room',
        'room.reservations',
    ];

    /**
     * @var int
     */
    protected $perPage = 10;

    protected static function boot()
    {
        parent::boot();

        self::updating(function ($model) {
            /** @var Reservation $model */
            if ($model->isDirty('guest_id')) {
                $model->detail->copyGuestData(Guest::find($model->guest_id));
            }
            if ($model->isDirty('room_id')) {
                $model->detail->copyRoomData(Room::find($model->room_id));
            }
            $model->detail->save();
        });
    }

    /**
     * @return array
     */
    public static function getSearchRules(): array
    {
        return [
            'start_date' => 'filled|date',
            'end_date'   => 'filled|date',
            'room_id'    => 'filled|integer|exists:rooms,id',
        ];
    }

    /**
     * @return array
     */
    public static function getSearchAttributes(): array
    {
        return [
            'start_date' => __('request.reservations.start_date'),
            'end_date'   => __('request.reservations.end_date'),
            'room_id'    => __('request.reservations.room_id'),
        ];
    }

    /**
     * @param  Builder  $query
     * @param  array  $conditions
     */
    protected static function setConditions(Builder $query, array $conditions)
    {
        if (! empty($conditions['s'])) {
            collect($conditions['s'])->each(function ($search) use ($query) {
                $query->where(function ($builder) use ($search) {
                    /** @var Builder $builder */
                    $builder->where('reservation_details.guest_name', 'like', "%{$search}%")
                            ->orWhere('reservation_details.guest_name_kana', 'like', "%{$search}%")
                            ->orWhere('reservation_details.guest_address', 'like', "%{$search}%")
                            ->orWhere('reservation_details.room_name', 'like', "%{$search}%");
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
    protected static function getSearchJoins(): array
    {
        return [
            'reservation_details' => [
                'first'  => 'reservation_details.reservation_id',
                'second' => 'reservations.id',
            ],
        ];
    }

    /**
     * @return array
     */
    protected static function getSearchOrderBy(): array
    {
        return [
            'reservations.start_date' => 'desc',
            'reservations.id'         => 'desc',
        ];
    }

    /**
     * @return array
     */
    public static function getCrudListRelations(): array
    {
        return [
            'guest',
        ];
    }


    /**
     * @return array
     */
    public static function getCrudDetailRelations(): array
    {
        return [
            'guest',
        ];
    }

    /**
     * @return array
     */
    public static function getCrudUpdateRelations(): array
    {
        return [
            'detail' => ReservationDetail::class,
        ];
    }

    /**
     * @param  array  $rules
     * @param  string  $name
     * @param  Column  $column
     * @param  bool  $isUpdate
     * @param  int|null  $primaryId
     * @param  FormRequest  $request
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function filterCrudRules(array $rules, string $name, Column $column, bool $isUpdate, ?int $primaryId, FormRequest $request): array
    {
        if ('reservations.end_date' === $name) {
            $rules[] = 'after_or_equal:reservations.start_date';
        }
        if ('reservation_details.number' === $name) {
            $rules['min'] = 'min:1';

            if ($isUpdate) {
                $roomId = request()->input('reservations.room_id', static::findOrFail($primaryId)->room_id);
            } else {
                $roomId = request()->input('reservations.room_id');
            }
            if ($roomId) {
                $room = Room::find($roomId);
                if ($room) {
                    $rules['max'] = 'max:'.$room->number;
                }
            }
        }
        if (in_array($name, [
            'reservation_details.number',
            'reservation_details.room_name',
            'reservation_details.guest_name',
            'reservation_details.guest_name_kana',
            'reservation_details.guest_zip_code',
            'reservation_details.guest_address',
            'reservation_details.guest_phone',
        ])) {
            unset($rules['filled']);
            unset($rules['required']);
            $rules['nullable'] = 'nullable';
        }

        return self::addReservationRule($rules, $name, $request);
    }

    /**
     * @param  array  $rules
     * @param  string  $name
     * @param  FormRequest  $request
     *
     * @return array
     */
    private static function addReservationRule(array $rules, string $name, FormRequest $request)
    {
        if ($request->has('reservations.start_date')) {
            if ('reservations.start_date' === $name) {
                $rules[] = 'reservation_term';
                $rules[] = 'reservation_availability';
                $rules[] = 'reservation_duplicate';
            }
        } elseif ($request->has('reservations.end_date')) {
            if ('reservations.end_date' === $name) {
                $rules[] = 'reservation_term';
                $rules[] = 'reservation_availability';
                $rules[] = 'reservation_duplicate';
            }
        } elseif ($request->has('reservations.room_id')) {
            if ('reservations.room_id' === $name) {
                $rules[] = 'reservation_availability';
            }
        } elseif ($request->has('reservations.guest_id')) {
            if ('reservations.guest_id' === $name) {
                $rules[] = 'reservation_duplicate';
            }
        }

        return $rules;
    }

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(ReservationDetail::class);
    }

    /**
     * @return BelongsTo
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class)->without('reservations');
    }

    /**
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->without('reservations');
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
        return static::getCheckinDatetime($this->start_date_str);
    }

    /**
     * @return Carbon|\Carbon\Carbon
     */
    public function getEndDatetimeAttribute(): Carbon
    {
        return static::getCheckoutDatetime($this->end_date_str, $this->checkout)->addDay();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsPastAttribute(): bool
    {
        return $this->end_datetime->timestamp < static::now();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsPresentAttribute(): bool
    {
        return $this->start_datetime->timestamp <= static::now() && $this->end_datetime->timestamp >= static::now();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsFutureAttribute(): bool
    {
        return $this->start_datetime->timestamp > static::now();
    }

    /**
     * @return int
     */
    public function getNightsAttribute(): int
    {
        return $this->end_date->diffInDays($this->start_date) + 1;
    }

    /**
     * @return int
     */
    public function getDaysAttribute(): int
    {
        return $this->nights + 1;
    }

    /**
     * @return string
     */
    public function getStaysAttribute(): string
    {
        return sprintf(__('misc.reservations.stays'), $this->nights, $this->days);
    }

    /**
     * @return int
     */
    public function getChargeAttribute(): int
    {
        return $this->nights * $this->room->price;
    }

    /**
     * @return int
     */
    public function getPaymentAttribute(): int
    {
        return $this->detail->payment ?? 0;
    }

    /**
     * @param  string|null  $startDate
     * @param  string|null  $endDate
     * @param  string|null  $checkoutTime
     *
     * @return bool
     */
    public static function isTermValid(?string $startDate, ?string $endDate, ?string $checkoutTime): bool
    {
        if (empty($startDate) || empty($endDate)) {
            return true;
        }

        if (strtotime($startDate) > strtotime($endDate)) {
            return false;
        }

        $maxDay = Setting::getSetting('max_day');
        if ($maxDay > 0 && $maxDay <= Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate))) {
            return false;
        }

        if ($checkoutTime && static::today()->setTimeFromTimeString($checkoutTime)->isAfter(static::today()->setTimeFromTimeString(self::getCheckinTime()))) {
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
     * @return bool
     */
    public function checkin()
    {
        $this->status = 'checkin';

        return $this->save();
    }

    /**
     * @param  int|null  $payment
     *
     * @return bool
     */
    public function checkout(?int $payment = null)
    {
        $this->detail->paid($payment);
        $this->status = 'checkout';

        return $this->save();
    }

    /**
     * @return bool
     */
    public function cancel()
    {
        $this->status = 'canceled';

        return $this->save();
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
