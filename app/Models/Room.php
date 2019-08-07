<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\Traits\TimeHelper;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * App\Models\Room
 *
 * @property int $id
 * @property string $name 部屋名
 * @property int $number 最大人数
 * @property int $price 一泊の金額
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Reservation[] $reservations
 * @method static Builder|Room newModelQuery()
 * @method static Builder|Room newQuery()
 * @method static Builder|Room query()
 * @method static Builder|Room whereCreatedAt($value)
 * @method static Builder|Room whereId($value)
 * @method static Builder|Room whereName($value)
 * @method static Builder|Room whereNumber($value)
 * @method static Builder|Room wherePrice($value)
 * @method static Builder|Room whereUpdatedAt($value)
 * @property-read mixed $is_reserved
 * @property-read Reservation $latestReservation
 * @property-read Collection|Reservation[] $recentUsages
 * @property-read Reservation $latestUsage
 */
class Room extends Model
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
        'number' => 'int',
        'price'  => 'int',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'is_reserved',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'reservations',
    ];

    /**
     * @var array
     */
    protected $with = [
        'reservations',
    ];

    /**
     * @var int
     */
    protected $perPage = 10;

    /**
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * @return HasOne
     */
    public function latestReservation(): HasOne
    {
        return $this->hasOne(Reservation::class)->latest('start_date')->limit(1);
    }

    /**
     * @return HasOne
     */
    public function latestUsage(): HasOne
    {
        return $this->hasOne(Reservation::class)->where('start_date', '<=', $this->getCheckInThresholdDay()->format('Y-m-d'))->latest('start_date')->limit(1);
    }

    /**
     * @return HasMany
     */
    public function recentUsages(): HasMany
    {
        return $this->reservations()->where('start_date', '<=', $this->getCheckInThresholdDay()->format('Y-m-d'))->latest('start_date')->limit(5);
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsReservedAttribute(): bool
    {
        return ! $this->reservations->every(
            function ($row) {
                /** @var Reservation $row */
                return ! $row->is_present;
            }
        );
    }
}
