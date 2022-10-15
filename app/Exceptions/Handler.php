<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->renderable(function (AuthenticationException $exception) {
            return new JsonResponse([
                'message' => __('user.unauthenticated')
            ], 401);
        })->renderable(function (NotFoundHttpException $exception) {
            return new JsonResponse([
                'message' => 'Не найдено'
            ], 404);
        })->renderable(function (OrderStatusException|UserIsNotOwnerException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage()
            ], 403);
        })->renderable(function (Throwable $exception) {
            if (! \App::isProduction()) {
                return new JsonResponse([
                    'message' => 'Произошла ошибка',
                    'exception' => $exception->getMessage(),
                ], 500);
            }

            return new JsonResponse([
                'message' => 'Произошла ошибка',
            ], 500);
        });
    }
}
