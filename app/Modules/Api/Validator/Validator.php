<?php

namespace App\Modules\Api\Validator;

use App\Modules\Api\Conditions\Condition;
use App\Modules\Api\Errors\ApiError;
use Illuminate\Http\Request;
use Throwable;

class Validator
{
    /**
     * @param Request $request
     * @param Condition[] $conditions
     * @return void
     * @throws Throwable
     */
    public static function validate(Request $request, array $conditions): void
    {
        foreach ($conditions as $condition) {
            $error = $condition->validate($request);

            throw_if(is_a($error, ApiError::class), $error);
        }
    }
}