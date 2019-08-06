<?php
declare(strict_types=1);

namespace Tests;

use DB;

trait TestHelper
{
    protected function getTables()
    {
        return [
            'password_resets',
            'settings',
            'admins',
            'reservations',
            'guest_details',
            'guests',
            'rooms',
        ];
    }

    protected function dropTables()
    {
        collect($this->getTables())->each(function ($table) {
            DB::statement('DROP TABLE IF EXISTS `'.$table.'`');
        });
    }

    protected function truncateTables()
    {
        $this->dropTables();
        $this->runMigrate();
    }

    protected function runMigrate()
    {
        $this->artisan('migrate:refresh');
    }

    protected function runSeed(array $params = [])
    {
        $this->artisan('db:seed', $params);
    }
}
