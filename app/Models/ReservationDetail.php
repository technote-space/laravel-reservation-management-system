<?php
declare(strict_types=1);

namespace App\Models;

use Closure;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ReservationDetail
 *
 * @property-read Reservation $reservation
 * @method static Builder|ReservationDetail newModelQuery()
 * @method static Builder|ReservationDetail newQuery()
 * @method static Builder|ReservationDetail query()
 * @mixin Eloquent
 * @property int $id
 * @property int $reservation_id 予約ID
 * @property int|null $number 利用人数
 * @property int|null $payment 支払金額
 * @property string $room_name 部屋名
 * @property string $guest_name 利用者名
 * @property string $guest_name_kana 利用者カナ名
 * @property string $guest_zip_code 利用者郵便番号
 * @property string $guest_address 利用者住所
 * @property string $guest_phone 利用者電話番号
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ReservationDetail whereCreatedAt($value)
 * @method static Builder|ReservationDetail whereGuestAddress($value)
 * @method static Builder|ReservationDetail whereGuestName($value)
 * @method static Builder|ReservationDetail whereGuestNameKana($value)
 * @method static Builder|ReservationDetail whereGuestPhone($value)
 * @method static Builder|ReservationDetail whereGuestZipCode($value)
 * @method static Builder|ReservationDetail whereId($value)
 * @method static Builder|ReservationDetail whereNumber($value)
 * @method static Builder|ReservationDetail wherePayment($value)
 * @method static Builder|ReservationDetail whereReservationId($value)
 * @method static Builder|ReservationDetail whereRoomName($value)
 * @method static Builder|ReservationDetail whereUpdatedAt($value)
 */
class ReservationDetail extends Model
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
        'reservation_id' => 'int',
        'number'         => 'int',
        'payment'        => 'int',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            /** @var ReservationDetail $model */
            $model->copyGuestData();
            $model->copyRoomData();
        });
    }

    /**
     * @return BelongsTo
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * @param  string  $target
     * @param  bool  $force
     * @param  Closure  $callback
     */
    private function fillData(string $target, bool $force, Closure $callback)
    {
        if ($force || ! isset($this->$target)) {
            $this->$target = $callback();
        }
    }

    /**
     * @param  Guest|null  $model
     */
    public function copyGuestData(?Guest $model = null)
    {
        collect([
            'name',
            'name_kana',
            'zip_code',
            'address',
            'phone',
        ])->each(function (string $key, $target) use ($model) {
            if (is_int($target)) {
                $target = 'guest_'.$key;
            }
            $this->fillData($target, isset($model), function () use ($model, $key) {
                return ($model ? $model : $this->reservation->guest)->detail->$key;
            });
        });
    }

    /**
     * @param  Room  $model
     */
    public function copyRoomData(?Room $model = null)
    {
        collect([
            'number' => 'number',
            'name',
        ])->each(function (string $key, $target) use ($model) {
            if (is_int($target)) {
                $target = 'room_'.$key;
            }
            $this->fillData($target, isset($model), function () use ($model, $key) {
                return ($model ? $model : $this->reservation->room)->$key;
            });
        });
    }

    /**
     * @param  int|null  $payment
     *
     * @return bool
     */
    public function paid(?int $payment = null)
    {
        $this->payment = $payment ?? $this->reservation->charge;

        return $this->save();
    }
}
