<?php

namespace App\Modules\User\Dto;

use Spatie\LaravelData\Data;

class CreateUserData extends Data
{
    public string $name;

    public string $password;

    public string $email;
}