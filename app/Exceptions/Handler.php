<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\handleHttp;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use handleHttp;
    
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            $code = method_exists($exception, 'getStatusCode');
            if (method_exists($exception, 'getStatusCode')) {
                $statusCode = $this->prepareException($exception)->getStatusCode();
                return $this->HandleCode($statusCode);
            } else {
                    $statusCode = 500;
            }
        }

        if (empty($request->is('api/*'))) {
            return parent::render($request, $exception);
        } elseif ($request->is('api/*') && auth('sanctum')->check() == false || empty($request->header('Authorization'))){
            $response = [
                'code'      =>401,
                'error'     =>true,
                'data'      =>[
                    'message' =>'Unauthorized Access',
                ],
            ];
                return response()->json($response,401);
            }
                return parent::render($request, $exception);
    }
}
