<?php

namespace App\Modules\User\Requests;

use App\Modules\Api\Handlers\ApiRequestHandler;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\Api\Helpers\ApiWith;
use App\Modules\User\Conditions\UserDoesExist;
use App\Modules\User\Dto\UpdateUserData;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class UpdateUser extends ApiRequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {
    }

    public function getConditions(): array
    {
        return [
            new UserDoesExist($this->getPathParameterAsInteger('id'))
        ];
    }

    public function processRequest(Request $request): Response
    {
        $userDto = UpdateUserData::from($request);

        $user = $this->service->update($this->getPathParameterAsInteger('id'), $userDto);

        $result = $this->transformer->transform($user, ApiWith::createWith());

        return ApiResponse::success($result);
    }
}
