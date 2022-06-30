<?php

namespace App\Modules\OpenApi\Controllers;

use App\Modules\Api\Errors\ApiError;
use App\Modules\Api\Handlers\RequestHandler;
use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Handlers\OpenApiHandler;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\OpenAPIValidation\PSR7\SpecFinder;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class OpenApiController
{
    public function __construct(private ServerRequestInterface $serverRequest, private OpenApiValidator $validator) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $context = $this->validator->validateRequest($this->serverRequest);
        } catch (OpenApiError $e) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);
            return new JsonResponse($data, $e->getCode());
        } catch (Throwable $e){
            return new JsonResponse(['message' => 'Internal Error', 'errors' => [$e->getMessage()]], \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $handlerClass = $this->getHandlerClass($context);
            /** @var RequestHandler $handler */
            $handler = app($handlerClass);
            $response = $handler->__invoke($request);
            $this->validator->validateResponse($context, $response);
        } catch (ApiError $e) {
            $data = array_filter([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ]);

            $response = new Response($e->getCode(), ['Content-Type' => 'application/json'], json_encode($data));
            $this->validator->validateResponse($context, $response);
        } catch (Throwable $e){
            $response = new Response(
                \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => 'Internal Error', 'errors' => [$e->getMessage()]])
            );
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