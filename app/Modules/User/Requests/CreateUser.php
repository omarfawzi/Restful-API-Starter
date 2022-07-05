<?php

namespace App\Modules\User\Requests;

use App\Modules\Api\Helpers\ApiWith;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\Api\Validator\Validator;
use App\Modules\OpenApi\Handlers\RequestHandler;
use App\Modules\User\Conditions\UserDoesNotExist;
use App\Modules\User\Dto\CreateUserData;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class CreateUser extends RequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {}

    public function __invoke(Request $request): Response
    {
        Validator::validate($request, [
            new UserDoesNotExist()
        ]);

        $userDto = CreateUserData::from($request);

        $user = $this->service->create($userDto);

        $result = $this->transformer->transform($user, ApiWith::createWith('token'));

        return ApiResponse::created($result);
    }
}
