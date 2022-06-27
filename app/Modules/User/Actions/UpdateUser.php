<?php

namespace App\Modules\User\Actions;

use App\Modules\Api\Handlers\RequestHandler;
use App\Modules\Api\Utilities\ApiWith;
use App\Modules\OpenApi\Utilities\OpenApiResponse;
use App\Modules\User\Conditions\UserDoesExist;
use App\Modules\User\Dto\UserDto;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateUser extends RequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {
    }

    public function getValidators(Request $request): array
    {
        return [
            new UserDoesExist($this->getPathParameterAsInteger('id'))
        ];
    }

    public function processRequest(Request $request): JsonResponse
    {
        $userDto = UserDto::fromRequest($request);

        $user = $this->service->update($this->getPathParameterAsInteger('id'), $userDto);

        $result = $this->transformer->transform($user, ApiWith::createWithDefault());

        return OpenApiResponse::success($request, $result);
    }
}