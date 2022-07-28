<?php

namespace App\Modules\OpenApi\Factories;

use App\Modules\Api\ApiHandler;
use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Handlers\RequestHandler;
use Exception;
use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\SpecFinder;

class RequestHandlerFactory
{
    /**
     * @throws Exception
     */
    public function make(OpenApiContext $context, string $path): RequestHandler
    {
        $specFinder = new SpecFinder($context->openApi);

        $operation = $specFinder->findOperationSpec($context->operationAddress);

        if (false === array_key_exists($operation->operationId, ApiHandler::MAP)){
            throw new InvalidArgumentException(
                sprintf(
                    'Operation %s not implemented, please add the operation and implementation to %s::MAP',
                    $operation->operationId,
                    ApiHandler::class
                )
            );
        }

        $pathParams = $context->operationAddress->parseParams($path);

        /** @var RequestHandler $handler */
        $handler = app(ApiHandler::MAP[$operation->operationId]);

        if (false === is_a($handler, RequestHandler::class))
        {
            throw new InvalidArgumentException(sprintf('Handler must be an instance of %s', RequestHandler::class));
        }

        $handler->setPathParameterBag($pathParams);
        
        return $handler;
    }
}
