<?php

namespace App\Modules\OpenApi\Controllers;

use App\Modules\Api\Errors\ApiError;
use App\Modules\Api\Handlers\RequestHandler;
use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Handlers\OpenApiHandler;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\OpenAPIValidation\PSR7\SpecFinder;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiController
{
    public function __construct(private ServerRequestInterface $serverRequest, private OpenApiValidator $validator) {}

    public function __invoke(Request $request): JsonResponse
    {
        $context = $this->validator->validateRequest($this->serverRequest);

        $handlerClass = $this->getHandlerClass($context);

        /** @var RequestHandler $handler */
        $handler = app($handlerClass);

        try {
            $response = $handler->__invoke($request);
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

    private function getHandlerClass(OpenApiContext $context): string
    {
        $specFinder = new SpecFinder($context->openApi);

        $operation = $specFinder->findOperationSpec($context->operationAddress);

        return OpenApiHandler::MAP[$operation->operationId];
    }
}