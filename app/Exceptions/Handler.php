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
        if ($request->is('api/*') || $request->is('api')) {

            $request->headers->set('Accept', 'application/json');

            if ($exception instanceof \Exception) {
                // return response()->json(['response' => "Project id:{$request->project} Not Found'"], 404);
                return response()->json(['response' => $exception->getMessage()]);
            }

            if ($exception instanceof NotFoundHttpException) {
                    return response()->json(['error' => 'Not Found'], 404);
            }

            if ($exception instanceof AuthenticationException) {
                    return response()->json(['message' => 'Unauthorised'], 401);
            }

        }

        return parent::render($request, $exception);
    }
}
