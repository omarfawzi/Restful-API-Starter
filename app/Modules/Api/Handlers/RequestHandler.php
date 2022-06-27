<?php

namespace App\Modules\Api\Handlers;

use App\Modules\Api\Conditions\Condition;
use App\Modules\Api\Validator\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class RequestHandler
{
    private Request $request;

    public function __invoke(Request $request): JsonResponse
    {
        $this->request = $request;

        Validator::validate($request, $this->getConditions($request));

        return $this->processRequest($request);
    }

    /**
     * @return Condition[]
     */
    abstract public function getConditions(Request $request): array;

    abstract public function processRequest(Request $request): JsonResponse;

    public function getPathParameterAsInteger(string $parameter): int
    {
        return (int) $this->request->route()->parameter($parameter);
    }

    public function getPathParameterAsString(string $parameter): string
    {
        return $this->request->route()->parameter($parameter);
    }
}