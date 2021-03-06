<?php

namespace App\Modules\OpenApi\Factories;

use App\Modules\Api\ApiHandler;
use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Handlers\RequestHandler;
use Exception;
use Illuminate\Http\Request;
use League\OpenAPIValidation\PSR7\SpecFinder;

class RequestHandlerFactory
{
    /**
     * @throws Exception
     */
    public function make(OpenApiContext $context, Request $request): RequestHandler
    {
        $specFinder = new SpecFinder($context->openApi);

        $operation = $specFinder->findOperationSpec($context->operationAddress);

        $pathParams = $context->operationAddress->parseParams($request->path());

        if (false === array_key_exists($operation->operationId, ApiHandler::MAP)){
            throw new Exception(
                sprintf(
                    'Operation %s not implemented, please add the operation and implementation to %s::MAP',
                    $operation->operationId,
                    ApiHandler::class
                )
            );
        }

        /** @var RequestHandler $handler */
        $handler = app(ApiHandler::MAP[$operation->operationId]);

        if (false === is_a($handler, RequestHandler::class))
        {
            throw new Exception(sprintf('Handler must be an instance of %s', RequestHandler::class));
        }

        $handler->setPathParameterBag($pathParams);
        
        return $handler;
    }
}
