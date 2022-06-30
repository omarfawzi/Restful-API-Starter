<?php

namespace App\Exceptions;

use App\Modules\OpenApi\Errors\OpenApiError;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];

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
        $this->registerErrorHandlers();
    }

    private function registerErrorHandlers(): void
    {
        $this->handleOpenApiError();
    }

    private function handleOpenApiError(): void
    {
        $this->renderable(function (OpenApiError $e) {
            $data = [
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ];

            return response()->json($data, $e->getCode());
        });
    }
}
