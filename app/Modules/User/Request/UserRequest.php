<?php

namespace App\Modules\User\Request;

use App\Modules\Api\Dto\ApiRequest;

class UserRequest extends ApiRequest
{
    public string $name;

    public ?string $password = null;

    public ?string $email = null;
}