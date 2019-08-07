<?php
declare(strict_types=1);

namespace Tests;

abstract class BaseTestCase extends TestCase
{
    use TestHelper;

    public function setUp(): void
    {
        parent::setUp();

        static::truncateTables();
        static::seeder();
    }

    protected static function seeder(): void
    {
    }
}
