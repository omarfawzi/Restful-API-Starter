<?php

namespace App\Modules\OpenApi\Controllers;

use App\Modules\Api\Errors\ApiError;
use App\Modules\Api\Handlers\RequestHandler;
use App\Modules\Api\ApiHandler;
use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Errors\OpenApiError;
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
    public function __construct(private ServerRequestInterface $serverRequest, private OpenApiValidator $validator)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $responseOrContext = $this->handleRequest();

        if ($responseOrContext instanceof JsonResponse) {
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
            $response = $this->getRequestHandler($context)->__invoke($request);
        } catch (ApiError $e) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);

            $response = new Response($e->getCode(), ['Content-Type' => 'application/json'], json_encode($data));
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
     */
    private function getRequestHandler(OpenApiContext $context): RequestHandler
    {
        $specFinder = new SpecFinder($context->openApi);

        $operation = $specFinder->findOperationSpec($context->operationAddress);

        if (false === array_key_exists($operation->operationId, ApiHandler::MAP)) {
            throw new Exception(
                sprintf(
                    'Operation %s not implemented, please add the operation and implementation to %s::MAP',
                    $operation->operationId,
                    ApiHandler::class
                )
            );
        }

        return app(ApiHandler::MAP[$operation->operationId]);
    }
}