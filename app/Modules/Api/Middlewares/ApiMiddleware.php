<?php

namespace App\Modules\Api\Middlewares;

use App\Modules\Api\Errors\ApiError;
use App\Modules\OpenApi\Middlewares\Middleware;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Psr\Http\Message\ServerRequestInterface;

class ApiMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param ServerRequestInterface $serverRequest
     * @return bool
     * @throws ApiError
     */
    public function handle(ServerRequestInterface $serverRequest): bool
    {
        if (false === $serverRequest->hasHeader('Authorization')) {
            return false;
        }

        $header = $serverRequest->getHeader('Authorization')[0];

        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        } else {
            return false;
        }

        if (null === PersonalAccessToken::findToken($token)) {
            throw new ApiError('Bearer Token is missing or not found', [], Response::HTTP_UNAUTHORIZED);
        }

        return true;
    }
}
