<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
        $this->renderable(function (NotFoundHttpException $exception, Request $request) {
            if($request->is('api/*')) {
                return response()->json([
                    'message' => 'resource not found'
                ], 404);
            }
        });

        $this->renderable(function (ValidationException $exception, Request $request) {
            if($request->is('api/*')) {
                return response()->json([
                    'status' => 'request failed',
                    'errors' => $exception->errors()
                ], 200);
            }
        });

        /*
        $this->reportable(function (Throwable $e) {
            //
        });
        */
    }
}
