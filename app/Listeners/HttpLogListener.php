<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\HttpException;
use Illuminate\Support\Facades\Log;

class HttpLogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HttpException  $event
     */
    public function handle(HttpException $event)
    {
        Log::error($event->exception->getMessage());
        Log::error($event->exception->getTraceAsString());
        Log::error($event->request->getMethod());
        Log::error($event->request->getUri());
    }
}
