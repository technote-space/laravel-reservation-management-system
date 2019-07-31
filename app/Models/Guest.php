<?php
declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

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
 * @mixin Eloquent
 */
class Guest extends Model
{
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
    ];

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
}
