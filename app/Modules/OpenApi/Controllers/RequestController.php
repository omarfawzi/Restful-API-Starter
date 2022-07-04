<?php

namespace App\Modules\OpenApi\Controllers;

use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Illuminate\Http\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class RequestController
{
    public function __construct(private OpenApiValidator $validator)
    {
    }

    public function control(ServerRequestInterface $serverRequest): OpenApiContext|JsonResponse
    {
        try {
            return $this->validator->validateRequest($serverRequest);
        } catch (OpenApiError $e) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);
            return new JsonResponse($data, $e->getCode());
        }
    }
}