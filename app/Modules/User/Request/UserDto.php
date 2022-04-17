<?php

namespace App\Modules\User\Request;

use App\Modules\Api\Dto\ApiDto;

class UserDto extends ApiDto
{
    public string $name;

    public ?string $password = null;

    public ?string $email = null;
}