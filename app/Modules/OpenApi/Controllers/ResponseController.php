<?php

namespace App\Modules\OpenApi\Controllers;

use App\Modules\Api\Errors\ApiError;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Factories\RequestHandlerFactory;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class ResponseController
{
    public function __construct(private OpenApiValidator $validator, private RequestHandlerFactory $factory)
    {
    }

    /**
     * @param Request $request
     * @param OpenApiContext $context
     * @return JsonResponse
     * @throws Exception
     */
    public function control(Request $request, OpenApiContext $context): JsonResponse
    {
        try {
            $response = $this->factory->make($context, $request)->__invoke($request);
            $this->validator->validateResponse($context, $response);
        } catch (OpenApiError|ApiError $e) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);

            $response = new Response($e->getCode(), ApiResponse::RESPONSE_HEADERS, json_encode($data));
        }

        return new JsonResponse(json_decode((string)$response->getBody(), true), $response->getStatusCode());
    }
}