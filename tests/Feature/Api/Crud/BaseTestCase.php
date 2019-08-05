<?php
declare(strict_types=1);

namespace Tests\Feature\Api\Crud;

use App\Models\Setting;

/**
 * Class BaseTestCase
 * @package Tests\Feature
 */
abstract class BaseTestCase extends \Tests\BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->runSeed([
            '--class' => 'SettingTableSeeder',
        ]);
        Setting::clearCache();
    }
}
