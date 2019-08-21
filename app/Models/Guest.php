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
use Technote\CrudHelper\Models\Contracts\Crudable as CrudableContract;
use Technote\CrudHelper\Models\Traits\Crudable;
use Technote\SearchHelper\Models\Contracts\Searchable as SearchableContract;
use Technote\SearchHelper\Models\Traits\Searchable;

/**
 * App\Models\Guest
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read GuestDetail $detail
 * @property-read Collection|Reservation[] $reservations
 * @method static Builder|Guest newModelQuery()
 * @method static Builder|Guest newQuery()
 * @method static Builder|Guest query()
 * @method static Builder|Guest whereCreatedAt($value)
 * @method static Builder|Guest whereId($value)
 * @method static Builder|Guest whereUpdatedAt($value)
 * @property-read Reservation $latestReservation
 * @property-read Reservation $latestUsage
 * @property-read Collection|Reservation[] $recentUsages
 * @mixin Eloquent
 * @mixin Builder
 */
class Guest extends Model implements CrudableContract, SearchableContract
{
    use TimeHelper, Crudable, Searchable;

    /**
     * @var array
     */
    protected $guarded = [
        'id',
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
        'detail',
        'reservations',
    ];

    /**
     * @var int
     */
    protected $perPage = 10;

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
                    $builder->where('guest_details.name', 'like', "%{$search}%")
                            ->orWhere('guest_details.name_kana', 'like', "%{$search}%")
                            ->orWhere('guest_details.zip_code', 'like', "%{$search}%")
                            ->orWhere('guest_details.address', 'like', "%{$search}%")
                            ->orWhere('guest_details.phone', 'like', "%{$search}%");
                });
            });
        }
    }

    /**
     * @return array
     */
    protected static function getSearchJoins(): array
    {
        return [
            'guest_details' => [
                'first'  => 'guest_details.guest_id',
                'second' => 'guests.id',
            ],
        ];
    }

    /**
     * @return array
     */
    protected static function getSearchOrderBy(): array
    {
        return [
            'guests.id' => 'desc',
        ];
    }

    /**
     * @return array
     */
    public static function getCrudUpdateRelations(): array
    {
        return [
            'detail' => GuestDetail::class,
        ];
    }

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(GuestDetail::class);
    }

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
}
