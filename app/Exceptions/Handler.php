<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Events\HttpException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  Exception  $exception
     *
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Exception  $exception
     *
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return $this->redirectBack($request, 'csrfトークンが無効です、再送信してください。', $exception);
        }
        if ($exception instanceof ModelNotFoundException ||
            $exception instanceof MethodNotAllowedHttpException) {
            return $this->redirectBack($request, 'ページが見つかりません.', $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * @param  Request  $request
     * @param  string  $errorMessage
     * @param  Exception  $exception
     *
     * @return Response
     */
    private function redirectBack(Request $request, string $errorMessage, Exception $exception): Response
    {
        event(new HttpException($exception, $request));
        if (session()->has('redirected')) {
            return parent::render($request, $exception);
        }

        return redirect()
            ->back()
            ->withInput($request->except(['_token']))
            ->with([
                'error'      => $errorMessage,
                'redirected' => true,
            ]);
    }
}
