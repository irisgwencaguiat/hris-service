<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = ['password', 'password_confirmation'];

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return customResponse()
                ->data(null)
                ->message('The identifier you are querying does not exist')
                ->failed(404)
                ->generate();
        }

        if ($e instanceof AuthorizationException) {
            return customResponse()
                ->data(null)
                ->message('You do not have the right to access this resource')
                ->failed(403)
                ->generate();
        }

        return parent::render($request, $e);
    }

    public function unauthenticated(
        $request,
        AuthenticationException $exception
    ) {
        // return parent::render($request, $exception);


        return customResponse()
            ->data([
                'request' => $request,
                'e' => $exception
            ])
            ->message('You do not have valid authentication token')
            ->failed(401)
            ->generate();
    }
}
