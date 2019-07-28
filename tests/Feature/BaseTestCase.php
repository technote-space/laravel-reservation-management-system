<?php
declare(strict_types=1);

namespace Tests\Feature;

use Artisan;

/**
 * Class BaseTestCase
 * @package Tests\Feature
 */
abstract class BaseTestCase extends \Tests\BaseTestCase
{
    protected static function seeder()
    {
        Artisan::call('db:seed');
    }
}
