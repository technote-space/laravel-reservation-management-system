<?php
declare(strict_types=1);

namespace Tests;

use Artisan;
use DB;
use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var bool $migrated */
    private static $migrated = false;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments(
            [
                '--disable-gpu',
                '--headless',
                '--window-size=1920,1080',
                '--no-sandbox',
            ]
        );

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options)
        );
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$migrated = false;
    }

    public function setUp(): void
    {
        parent::setUp();

        // 一度だけテスト用DBに対してマイグレーションを実行する
        if (! self::$migrated) {
            self::$migrated = true;
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach (DB::select('SHOW TABLES') as $table) {
                $columnName = 'Tables_in_'.DB::connection()->getDatabaseName();
                DB::statement('DROP TABLE IF EXISTS `'.$table->$columnName.'`');
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Artisan::call('migrate');
            Artisan::call('db:seed');
        }
    }
}
