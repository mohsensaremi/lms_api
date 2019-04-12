<?php

namespace App\Exceptions;

use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            $errors = collect($exception->errors())->reduce(function ($acc, $item) {
                return array_merge($acc, array_values($item));
            }, []);
            $exception = new HttpResponseException($errors, $exception->status);
        } else if ($exception instanceof ExpiredException) {
            $exception = new HttpResponseException(['Token Expired Exception'], 403);
        } else if ($exception instanceof \UnexpectedValueException) {
            $exception = new HttpResponseException(['Unexpected Value Exception'], 403);
        }

        return parent::render($request, $exception);
    }
}
