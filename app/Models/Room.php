<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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
 * @mixin Eloquent
 */
class Room extends Model
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
    protected $casts = [
        'number' => 'int',
        'price'  => 'int',
    ];

    /**
     * @return HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
