<?php

namespace App\Modules\User\Conditions;

use App\Modules\Api\Conditions\Condition;
use App\Modules\Api\Errors\ApiError;
use Illuminate\Http\Request;

class HasPassword implements Condition
{

    public function validate(Request $request): ?ApiError
    {
        if (false === $request->has('password')) {
            return new ApiError('Password should exist on create.', ['password' => 'field is required on create']);
        }

        return null;
    }
}