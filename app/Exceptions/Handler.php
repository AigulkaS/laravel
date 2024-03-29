<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });

        // $this->renderable(function (Exception $e, $request) {
        //     error_log('bbbbb');
        //     error_log(get_class($e));
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'message' => 'Record not found.'
        //         ], 404);
        //     }
        // });

        // $this->renderable(function (NotFoundHttpException $e, $request) {
        //     if ($request->is('api/*')) {
        //       if ($e->getPrevious() instanceof ModelNotFoundException) {
        //           return response()->json([
        //               'status' => 204,
        //               'message' => 'Data not found'
        //           ], 200);
        //       }
        //       return response()->json([
        //           'status' => 404,
        //           'message' => 'Target not found'
        //       ], 404);
        //     }
        //   });
    }
}
