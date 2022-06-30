<?php

namespace App\Modules\Api\Handlers;

use App\Modules\Api\Conditions\Condition;
use App\Modules\Api\Validator\Validator;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

abstract class RequestHandler
{
    private Request $request;

    public function __invoke(Request $request): Response
    {
        $this->request = $request;

        Validator::validate($request, $this->getConditions());

        return $this->processRequest($request);
    }

    /**
     * @return Condition[]
     */
    abstract public function getConditions(): array;

    abstract public function processRequest(Request $request): Response;

    public function getPathParameterAsInteger(string $parameter): int
    {
        return (int) $this->request->route()->parameter($parameter);
    }

    public function getPathParameterAsString(string $parameter): string
    {
        return $this->request->route()->parameter($parameter);
    }
}
