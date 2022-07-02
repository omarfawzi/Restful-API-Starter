<?php

namespace App\Modules\OpenApi\Controllers;

use App\Modules\Api\Errors\ApiError;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Factories\RequestHandlerFactory;
use App\Modules\OpenApi\Handlers\RequestHandler;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\OpenAPIValidation\PSR7\Exception\NoPath;
use League\OpenAPIValidation\PSR7\SpecFinder;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiController
{
    public function __construct(
        private ServerRequestInterface $serverRequest,
        private OpenApiValidator $validator,
        private RequestHandlerFactory $factory
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $responseOrContext = $this->handleRequest();

        if (is_a($responseOrContext, JsonResponse::class)) {
            return $responseOrContext;
        }

        return $this->handleResponse($request, $responseOrContext);
    }

    /**
     * @throws OpenApiError
     * @throws NoPath
     */
    private function handleResponse(Request $request, OpenApiContext $context): JsonResponse
    {
        try {
            $response = $this->getRequestHandler($request, $context)->__invoke($request);
        } catch (ApiError $e) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);

            $response = new Response($e->getCode(), ApiResponse::RESPONSE_HEADERS, json_encode($data));
        } finally {
            $this->validator->validateResponse($context, $response);
        }

        return new JsonResponse(json_decode((string)$response->getBody(), true), $response->getStatusCode());
    }

    private function handleRequest(): OpenApiContext|JsonResponse
    {
        try {
            return $this->validator->validateRequest($this->serverRequest);
        } catch (OpenApiError $e) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);
            return new JsonResponse($data, $e->getCode());
        }
    }

    /**
     * @throws NoPath
     * @throws Exception
     */
    private function getRequestHandler(Request $request, OpenApiContext $context): RequestHandler
    {
        $specFinder = new SpecFinder($context->openApi);

        $operation = $specFinder->findOperationSpec($context->operationAddress);

        $pathParams = $context->operationAddress->parseParams($request->path());

        return $this->factory->make($operation, $pathParams);
    }
}