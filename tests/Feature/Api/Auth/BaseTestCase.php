<?php
declare(strict_types=1);

namespace Tests\Feature\Api\Auth;

use Artisan;

/**
 * Class BaseTestCase
 * @package Tests\Feature
 */
abstract class BaseTestCase extends \Tests\BaseTestCase
{
    protected static function seeder(): void
    {
        Artisan::call('db:seed');
    }
}
