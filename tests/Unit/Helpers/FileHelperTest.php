<?php
declare(strict_types=1);

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use Tests\TestHelper;
use Tests\Unit\Helpers\Implement\FileHelperImplemented;

/**
 * Class FileHelperTest
 * @package Tests\Unit\Models
 * @group Unit
 * @group Unit.Helpers
 * @SuppressWarnings(PMD.TooManyPublicMethods)
 */
class FileHelperTest extends TestCase
{
    use TestHelper;

    /** @var FileHelperImplemented $helper */
    private static $helper;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        require_once __DIR__.'/Implement/FileHelperImplemented.php';
        self::$helper = new FileHelperImplemented();
    }

    public function setUp(): void
    {
        parent::setUp();
        static::truncateTables();
        static::runSeed([
            '--class' => 'SettingTableSeeder',
        ]);
    }

    public function testGetStorageUrl()
    {
        $this->assertStringStartsWith('http', self::$helper->getStorageUrl('https://localhost'));
        $this->assertStringStartsWith('http', self::$helper->getStorageUrl('test', 'public'));
    }

    public function testLoadJson()
    {
        $json = self::$helper->loadJson(resource_path('config/env.json'))->toArray();
        $this->assertTrue(is_array($json));
        $this->assertNotEmpty($json);
        $this->assertArrayHasKey('prod', $json);
        $this->assertArrayHasKey('dev', $json);

        $json = self::$helper->loadJson(resource_path('config/env.json.test'))->toArray();
        $this->assertTrue(is_array($json));
        $this->assertEmpty($json);
    }
}
