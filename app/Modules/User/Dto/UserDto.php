<?php

namespace App\Modules\User\Dto;

use Spatie\LaravelData\Data;

class UserDto extends Data
{
    public string $name;

    public ?string $password;

    public ?string $email;
}