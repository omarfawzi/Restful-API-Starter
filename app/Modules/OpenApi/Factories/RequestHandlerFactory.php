<?php

namespace App\Modules\OpenApi\Factories;

use App\Modules\Api\ApiHandler;
use App\Modules\OpenApi\Handlers\RequestHandler;
use cebe\openapi\spec\Operation;
use Exception;

class RequestHandlerFactory
{
    /**
     * @throws Exception
     */
    public function make(Operation $operation, array $pathParams): RequestHandler
    {
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

        $handler->setPathParameterBag($pathParams);

        if (false === is_a($handler, RequestHandler::class))
        {
            throw new Exception(sprintf('Handler must be instance of %s', RequestHandler::class));
        }

        return $handler;
    }
}