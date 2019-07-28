<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Guest;
use App\Models\GuestDetail;

/**
 * Class GuestDetailTest
 * @package Tests\Unit\Models
 * @group Models
 */
class GuestDetailTest extends BaseTestCase
{
    protected function getTarget(): string
    {
        return 'detail';
    }

    /** @var GuestDetail $detail */
    protected static $detail;

    /** @var Guest $guest */
    protected static $guest;

    protected static function seeder()
    {
        self::$guest  = factory(Guest::class)->create();
        self::$detail = factory(GuestDetail::class)->create([
            'guest_id' => self::$guest->id,
        ]);
    }

    public function belongToDataProvider(): array
    {
        return [
            [Guest::class, 'guest'],
        ];
    }
}
