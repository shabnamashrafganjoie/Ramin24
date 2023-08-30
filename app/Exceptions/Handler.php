<?php

namespace App\Exceptions;

use Error;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {


        if ($e instanceof ModelNotFoundException) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 404);
        }

        if ($e instanceof NotFoundHttpException) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }

        if ($e instanceof Exception) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }

        if ($e instanceof Error) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }

        if ($e instanceof QueryException) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }


        if ($e instanceof RelationNotFoundException) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }


  

        if (config('app.debug')) {
            DB::rollBack();
            return parent::render($request, $e);
        }

        DB::rollBack();
        return $this->errorResponse($e->getMessage(), 500);

         




      //  return parent::render($request, $exception);
    }
}
