<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('testing')) {
            $this->app->register(DuskServiceProvider::class);
            if ('sqlite' === config('database.default')) {
                $path = config('database.connections.sqlite.database');
                if (':memory:' !== $path && ! file_exists($path) && is_dir(dirname($path))) {
                    // @codeCoverageIgnoreStart
                    touch($path);
                    // @codeCoverageIgnoreEnd
                }
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
        //
    }
}
