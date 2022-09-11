<?php

namespace App\Modules\User\Conditions;

use App\Modules\Api\Conditions\Condition;
use App\Modules\Api\Errors\ApiError;
use App\Modules\User\Resolver\UserResolver;
use Illuminate\Http\Request;

class UserDoesNotExist implements Condition
{
    public function __construct(private UserResolver $userResolver){}

    public function validate(Request $request): ?ApiError
    {
        $user = $this->userResolver->resolveByEmail($request->get('email'));

        if (null !== $user) {
            return new ApiError('User already exists', ['email' => 'User already exists for such email.']);
        }

        return null;
    }
}
