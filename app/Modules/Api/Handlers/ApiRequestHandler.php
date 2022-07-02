<?php

namespace App\Modules\Api\Handlers;

use App\Modules\Api\Conditions\Condition;
use App\Modules\Api\Validator\Validator;
use App\Modules\OpenApi\Handlers\RequestHandler;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

abstract class ApiRequestHandler extends RequestHandler
{
    public function __invoke(Request $request): Response
    {
        Validator::validate($request, $this->getConditions());

        return $this->processRequest($request);
    }

    /**
     * @return Condition[]
     */
    abstract public function getConditions(): array;

    abstract public function processRequest(Request $request): Response;
}
