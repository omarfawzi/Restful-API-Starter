<?php

namespace App\Modules\User\Controllers;

use App\Modules\Api\Utilities\ApiFilter;
use App\Modules\Api\Utilities\Pagination;
use App\Modules\Api\Validator\Validator;
use App\Modules\OpenApi\Utilities\OpenApiResponse;
use App\Modules\User\Conditions\HasEmail;
use App\Modules\User\Conditions\HasPassword;
use App\Modules\User\Conditions\UserDoesExist;
use App\Modules\User\Conditions\UserDoesNotExist;
use App\Modules\User\Request\UserDto;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use App\Modules\User\With\UserWith;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {
    }

    public function create(Request $request): JsonResponse
    {
        Validator::validate($request, [
            new HasPassword(),
            new HasEmail(),
            new UserDoesNotExist()
        ]);

        $userDto = (new UserDto)->fromRequest($request);

        $user = $this->service->create($userDto);

        $result = $this->transformer->transform($user, (new UserWith)->createWithDefault());

        return OpenApiResponse::created($request, $result);
    }

    public function update(Request $request, int $id, UserDto $userDto): JsonResponse
    {
        Validator::validate($request, [
            new UserDoesExist($id)
        ]);

        $userDto = $userDto->fromRequest($request);

        $user = $this->service->update($id, $userDto);

        $result = $this->transformer->transform($user, new UserWith());

        return OpenApiResponse::success($request, $result);
    }

    public function show(Request $request, int $id, UserWith $userWith): JsonResponse
    {
        Validator::validate($request, [
            new UserDoesExist($id)
        ]);

        $user = $this->service->find($id);

        $userWith = $userWith->fromRequest($request);

        $result = $this->transformer->transform($user, $userWith);

        return OpenApiResponse::success($request, $result);
    }

    public function list(Request $request, Pagination $pagination, ApiFilter $apiFilter, UserWith $userWith): JsonResponse
    {
        $pagination = $pagination->fromRequest($request);

        $apiFilter = $apiFilter->fromRequest($request);

        $userWith = $userWith->fromRequest($request);

        $users = $this->service->list($pagination, $apiFilter);

        $result = $this->transformer->transformCollection($users, $pagination, $userWith);

        return OpenApiResponse::success($request, $result);
    }
}
