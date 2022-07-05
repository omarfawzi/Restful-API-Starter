<?php

namespace App\Modules\OpenApi\Controllers;

use App\Modules\Api\Errors\ApiError;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\OpenApi\Factories\RequestHandlerFactory;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiController
{
    public function __construct(
        private ServerRequestInterface $serverRequest,
        private RequestHandlerFactory $factory,
        private OpenApiValidator $validator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $context = $this->validator->validateRequest($this->serverRequest);

        try {
            $response = $this->factory->make($context, $request)->__invoke($request);
        } catch (ApiError $e) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);
            $response = new Response($e->getCode(), ApiResponse::RESPONSE_HEADERS, json_encode($data));
        }

        $this->validator->validateResponse($response, $context);

        return new JsonResponse(
            json_decode((string)$response->getBody(), true), $response->getStatusCode()
        );
    }
}
