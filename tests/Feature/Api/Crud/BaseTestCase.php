<?php
declare(strict_types=1);

namespace Tests\Feature\Api\Crud;

/**
 * Class BaseTestCase
 * @package Tests\Feature
 */
abstract class BaseTestCase extends \Tests\BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        static::runSeed([
            '--class' => 'SettingTableSeeder',
        ]);
    }
}
