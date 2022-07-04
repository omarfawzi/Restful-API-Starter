<?php

namespace App\Modules\Api\Responses;

use App\Modules\OpenApi\Errors\OpenApiError;
use Nyholm\Psr7\Response;

class ApiResponse
{
    public const RESPONSE_HEADERS = ['Content-Type' => 'application/json'];

    /**
     * @throws OpenApiError
     */
    public static function noContent(): Response
    {
        return self::send([], \Illuminate\Http\Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws OpenApiError
     */
    public static function created(array $data): Response
    {
        return self::send($data, \Illuminate\Http\Response::HTTP_CREATED);
    }

    /**
     * @throws OpenApiError
     */
    public static function success(array $data): Response
    {
        return self::send($data, \Illuminate\Http\Response::HTTP_OK);
    }

    /**
     * @throws OpenApiError
     */
    private static function send(array $data, int $statusCode): Response
    {
        return new Response($statusCode, self::RESPONSE_HEADERS, json_encode($data));
    }
}
