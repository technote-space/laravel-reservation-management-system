<?php
declare(strict_types=1);

namespace Tests;

use Artisan;
use DB;

abstract class BaseTestCase extends TestCase
{
    /** @var bool $initialized */
    private static $initialized = false;

    /** @var bool $migrated */
    private static $migrated = false;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$migrated = false;
    }

    public function setUp(): void
    {
        parent::setUp();
        // 一度だけテスト用DBに対してマイグレーションを実行する
        if (! self::$initialized) {
            self::$initialized = true;
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach (DB::select('SHOW TABLES') as $table) {
                $columnName = 'Tables_in_'.DB::connection()->getDatabaseName();
                DB::statement('DROP TABLE IF EXISTS `'.$table->$columnName.'`');
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Artisan::call('migrate');
        }
        if (! self::$migrated) {
            self::$migrated = true;
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach (DB::select('SHOW TABLES') as $table) {
                $columnName = 'Tables_in_'.DB::connection()->getDatabaseName();
                DB::statement('TRUNCATE TABLE `'.$table->$columnName.'`');
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            static::seeder();
        }
    }

    protected static function seeder()
    {
    }
}
