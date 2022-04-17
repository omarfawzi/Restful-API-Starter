<?php

namespace App\Modules\OpenApi\Utilities;

use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Validator\OpenApiValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class OpenApiResponse
{
    /**
     * @throws OpenApiError
     */
    public static function noContent(Request $request, array $data): JsonResponse
    {
        return self::send($request, $data, \Illuminate\Http\Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws OpenApiError
     */
    public static function created(Request $request, array $data): JsonResponse
    {
        return self::send($request, $data, \Illuminate\Http\Response::HTTP_CREATED);
    }

    /**
     * @throws OpenApiError
     */
    public static function success(Request $request, array $data): JsonResponse
    {
        return self::send($request, $data, \Illuminate\Http\Response::HTTP_OK);
    }

    /**
     * @throws OpenApiError
     */
    private static function send(Request $request, array $data, int $statusCode): JsonResponse
    {
        $validator = new OpenApiValidator();

        $response = new Response($statusCode, ['Content-Type' => 'application/json'], json_encode($data));

        $validator->validateResponse($request, $response);

        return new JsonResponse($data, $statusCode);
    }
}