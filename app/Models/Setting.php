<?php
declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Setting
 *
 * @property int $id
 * @property string $key 設定キー
 * @property string|null $value 設定値
 * @property string|null $type タイプ
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereCreatedAt($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereKey($value)
 * @method static Builder|Setting whereType($value)
 * @method static Builder|Setting whereUpdatedAt($value)
 * @method static Builder|Setting whereValue($value)
 * @mixin Eloquent
 */
class Setting extends Model
{
    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /** @var array $cache */
    private static $cache = [];

    /**
     * @param  string  $key
     *
     * @return string
     */
    protected function getCastType($key)
    {
        $row = static::where('key', $key)->first();
        if (empty($row)) {
            return '';
        }

        return $row->type;
    }

    /**
     * @param  string  $key
     *
     * @return mixed
     */
    public static function getSetting(string $key)
    {
        if (! array_key_exists($key, self::$cache)) {
            $row = static::where('key', $key)->first();
            if (empty($row)) {
                self::$cache[$key] = null;
            } else {
                self::$cache[$key] = $row->castAttribute($key, $row->value);
            }
        }

        return self::$cache[$key];
    }

    /**
     * clear cache
     */
    public static function clearCache()
    {
        self::$cache = [];
    }
}
