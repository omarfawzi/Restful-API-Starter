<?php

namespace App\Modules\User\Requests;

use App\Modules\Api\Handlers\ApiRequestHandler;
use App\Modules\Api\Helpers\ApiWith;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\User\Conditions\HasEmail;
use App\Modules\User\Conditions\HasPassword;
use App\Modules\User\Conditions\UserDoesNotExist;
use App\Modules\User\Dto\UserDto;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class CreateUser extends ApiRequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {}

    public function getConditions(): array
    {
        return [
            new HasPassword(),
            new HasEmail(),
            new UserDoesNotExist()
        ];
    }

    public function processRequest(Request $request): Response
    {
        $userDto = UserDto::from($request);

        $user = $this->service->create($userDto);

        $result = $this->transformer->transform($user, ApiWith::createWith('token'));

        return ApiResponse::created($result);
    }
}
