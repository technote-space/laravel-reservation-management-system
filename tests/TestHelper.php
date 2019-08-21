<?php
declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait TestHelper
{
    protected static function getTables()
    {
        return [
            'password_resets',
            'settings',
            'admins',
            'reservation_details',
            'reservations',
            'guest_details',
            'guests',
            'rooms',
            'migrations',
        ];
    }

    protected static function dropTables()
    {
        DB::getSchemaBuilder()->disableForeignKeyConstraints();
        collect(static::getTables())->each(function ($table) {
            DB::statement('DROP TABLE IF EXISTS `'.$table.'`');
        });
    }

    protected static function truncateTables()
    {
        static::dropTables();
        static::runMigrate();
    }

    protected static function runMigrate()
    {
        DB::getSchemaBuilder()->enableForeignKeyConstraints();
        Artisan::call('migrate:refresh');
    }

    protected static function runSeed(array $params = [])
    {
        Artisan::call('db:seed', $params);
    }
}
