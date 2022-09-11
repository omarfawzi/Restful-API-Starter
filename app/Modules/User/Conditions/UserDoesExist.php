<?php

namespace App\Modules\User\Conditions;

use App\Modules\Api\Conditions\Condition;
use App\Modules\Api\Errors\ApiError;
use App\Modules\User\Resolver\UserResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserDoesExist implements Condition
{
    public function __construct(private UserResolver $userResolver, private int $userId){}

    public function validate(Request $request): ?ApiError
    {
        $user = $this->userResolver->resolveById($this->userId);

        if (null == $user) {
            return new ApiError('User do not exist', ['id' => "User does not exist for id : $this->userId"], Response::HTTP_NOT_FOUND);
        }

        return null;
    }
}
