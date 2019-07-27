<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\GuestDetail
 *
 * @property int $id
 * @property int $guest_id 利用者ID
 * @property string $name 名
 * @property string $name_kana カナ名
 * @property string $zip_code 郵便番号
 * @property string $address 住所
 * @property string $phone 電話番号
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Guest $guest
 * @method static Builder|GuestDetail newModelQuery()
 * @method static Builder|GuestDetail newQuery()
 * @method static Builder|GuestDetail query()
 * @method static Builder|GuestDetail whereAddress($value)
 * @method static Builder|GuestDetail whereCreatedAt($value)
 * @method static Builder|GuestDetail whereGuestId($value)
 * @method static Builder|GuestDetail whereId($value)
 * @method static Builder|GuestDetail whereName($value)
 * @method static Builder|GuestDetail whereNameKana($value)
 * @method static Builder|GuestDetail wherePhone($value)
 * @method static Builder|GuestDetail whereUpdatedAt($value)
 * @method static Builder|GuestDetail whereZipCode($value)
 * @mixin Eloquent
 */
class GuestDetail extends Model
{
    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @return BelongsTo
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
