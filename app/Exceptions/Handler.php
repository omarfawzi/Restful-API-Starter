<?php

namespace App\Exceptions;

use App\Modules\Api\Errors\ApiError;
use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;
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
        $this->handleApiError();
        $this->handleOpenApiError();
    }

    private function handleApiError(): void
    {
        $this->renderable(function (ApiError $e, Request $request) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);
            $validator = new OpenApiValidator();

            $response = new Response($e->getCode(), ['Content-Type' => 'application/json'], json_encode($data));

            $validator->validateResponse($request, $response);

            return response()->json($data, $e->getCode());
        });
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
