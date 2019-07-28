<?php
declare(strict_types=1);

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Throwable;

/**
 * Class HttpException
 * @package App\Events
 */
class HttpException
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Throwable $exception */
    public $exception;

    /** @var Request $request */
    public $request;

    /**
     * HttpException constructor.
     *
     * @param  Throwable  $exception
     * @param  Request  $request
     */
    public function __construct(Throwable $exception, Request $request)
    {
        $this->exception = $exception;
        $this->request   = $request;
    }
}
