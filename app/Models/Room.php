<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\Traits\TimeHelper;
use Doctrine\DBAL\Schema\Column;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Technote\CrudHelper\Models\Contracts\Crudable as CrudableContract;
use Technote\CrudHelper\Models\Traits\Crudable;
use Technote\SearchHelper\Models\Contracts\Searchable as SearchableContract;
use Technote\SearchHelper\Models\Traits\Searchable;

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
 * @property-read Collection|Reservation[] $lastYearUsages
 * @mixin Eloquent
 * @mixin Builder
 * @property-read int $total_sales
 * @property-read int|null $last_year_usages_count
 * @property-read int|null $recent_usages_count
 * @property-read int|null $reservations_count
 */
class Room extends Model implements CrudableContract, SearchableContract
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
        'number' => 'int',
        'price'  => 'int',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'is_reserved',
        'total_sales',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'reservations',
        'lastYearUsages',
    ];

    /**
     * @var array
     */
    protected $with = [
        'reservations',
        'lastYearUsages',
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
                $query->where('rooms.name', 'like', "%{$search}%");
            });
        }
    }

    /**
     * @return array
     */
    protected static function getSearchOrderBy(): array
    {
        return [
            'rooms.id' => 'desc',
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
        if ('rooms.number' === $name) {
            $rules[] = 'min:1';
        }

        return $rules;
    }

    /**
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class)->without('room');
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
        return $this->hasOne(Reservation::class)->whereDate('start_date', '<=', $this->getCheckInThresholdDay())->latest('start_date')->limit(1);
    }

    /**
     * @return HasMany
     */
    public function recentUsages(): HasMany
    {
        return $this->reservations()->whereDate('start_date', '<=', $this->getCheckInThresholdDay())->latest('start_date')->limit(5);
    }

    /**
     * @return HasMany
     */
    public function lastYearUsages(): HasMany
    {
        return $this->reservations()->whereDate('start_date', '>=', now()->subYear())->whereDate('start_date', '<', now());
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

    /**
     * @return int
     */
    public function getTotalSalesAttribute(): int
    {
        return $this->lastYearUsages->sum(function ($row) {
            /** @var Reservation $row */
            return $row->detail->payment;
        });
    }
}
