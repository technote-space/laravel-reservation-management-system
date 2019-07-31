<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Setting;
use Tests\TestCase;
use Tests\TestHelper;

/**
 * Class RoomTest
 * @package Tests\Unit\Models
 * @group Unit
 * @group Unit.Models
 */
class SettingTest extends TestCase
{
    use TestHelper;

    public function setUp(): void
    {
        parent::setUp();

        $this->runMigrate();
        factory(Setting::class)->create([
            'key'   => 'test1',
            'value' => 'test1',
        ]);
        factory(Setting::class)->create([
            'key'   => 'test2',
            'value' => '2',
            'type'  => 'int',
        ]);
        factory(Setting::class)->create([
            'key'   => 'test3',
            'value' => '1',
            'type'  => 'bool',
        ]);
        factory(Setting::class)->create([
            'key'   => 'test4',
            'value' => '0',
            'type'  => 'bool',
        ]);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param  mixed  $expected
     * @param  string  $key
     */
    public function testGet($expected, $key)
    {
        $this->assertSame($expected, Setting::getSetting($key));
    }

    public function dataProvider()
    {
        return [
            ['test1', 'test1'],
            [2, 'test2'],
            [true, 'test3'],
            [false, 'test4'],
            [null, 'test5'],
        ];
    }
}
