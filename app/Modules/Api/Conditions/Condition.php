<?php

namespace App\Modules\Api\Conditions;

use App\Modules\Api\Errors\ApiError;
use Illuminate\Http\Request;

interface Condition
{
    public function validate(Request $request): ?ApiError;
}