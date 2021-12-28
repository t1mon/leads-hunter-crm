<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request,  $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            if ($request->is('api/*') || $request->is('api')) {
                return response()->json(['error' => 'Not Found'], 404);
            }
            //return response()->view('404', [], 404);
        }

        if ($exception instanceof AuthenticationException) {
            if ($request->is('api/*') || $request->is('api')) {
                return response()->json(['message' => 'Unauthorised'], 401);
            }
        }

        return parent::render($request, $exception);
    }
}
