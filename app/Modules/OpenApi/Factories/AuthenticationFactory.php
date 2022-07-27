<?php

namespace App\Modules\OpenApi\Factories;

use App\Modules\Api\Middlewares\ApiMiddleware;
use App\Modules\OpenApi\Middlewares\Middleware;
use InvalidArgumentException;

class AuthenticationFactory
{
    private const BEARER_AUTH = 'bearerAuth';

    public function make(string $securityMethod, array $context = []): Middleware
    {
        return match ($securityMethod) {
            self::BEARER_AUTH => new ApiMiddleware(),
            default => throw new InvalidArgumentException("Security method: $securityMethod is not supported for this operation."),
        };
    }
}