<?php

namespace App\Modules\Api\Middlewares;

use App\Modules\Api\Errors\ApiError;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Closure|JsonResponse
     * @throws ApiError
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->bearerToken();

        if (null === PersonalAccessToken::findToken($token))
        {
            throw new ApiError('Access Token is missing or not found', [],Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}