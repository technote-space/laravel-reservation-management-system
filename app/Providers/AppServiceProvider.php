<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ('sqlite' === config('database.default')) {
            $path = config('database.connections.sqlite.database');
            if (':memory:' !== $path && ! file_exists($path) && is_dir(dirname($path))) {
                // @codeCoverageIgnoreStart
                touch($path);
                // @codeCoverageIgnoreEnd
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
