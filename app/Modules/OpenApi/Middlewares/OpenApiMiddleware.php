<?php

namespace App\Modules\OpenApi\Middlewares;

use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiMiddleware
{
    public function __construct(private ServerRequestInterface $serverRequest) {}

    /**
     * Validates upcoming requests against the defined open api schema
     *
     * @param Request $request
     * @param Closure $next
     * @return Closure|JsonResponse
     * @throws OpenApiError
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $validator = new OpenApiValidator();

        $validator->validateRequest($this->serverRequest, $request);

        return $next($request);
    }
}