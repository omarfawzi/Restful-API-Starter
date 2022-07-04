<?php

namespace App\Modules\User\Conditions;

use App\Modules\Api\Conditions\Condition;
use App\Modules\Api\Errors\ApiError;
use Illuminate\Http\Request;

class HasEmail implements Condition
{
    public function validate(Request $request): ?ApiError
    {
        if (null === $request->get('email')){
            return new ApiError('Email is missing', ['email' => 'field is required on create']);
        }

        return null;
    }
}