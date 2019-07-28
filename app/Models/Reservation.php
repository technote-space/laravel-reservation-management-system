<?php
declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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
 */
class Reservation extends Model
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
        'start_date' => 'date',
        'end_date'   => 'date',
        'number'     => 'int',
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
