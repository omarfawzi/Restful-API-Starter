<?php

namespace App\Modules\OpenApi\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiController
{
    public function __construct(
        private ServerRequestInterface $serverRequest,
        private RequestController $requestController,
        private ResponseController $responseController
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $responseOrContext = $this->requestController->control($this->serverRequest);

        if (is_a($responseOrContext, JsonResponse::class)) {
            return $responseOrContext;
        }

        return $this->responseController->control($request, $responseOrContext);
    }
}