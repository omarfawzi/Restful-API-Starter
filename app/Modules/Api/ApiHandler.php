<?php

namespace App\Modules\Api;

use App\Modules\User\Requests\CreateUser;
use App\Modules\User\Requests\GetUser;
use App\Modules\User\Requests\GetUsers;
use App\Modules\User\Requests\UpdateUser;

final class ApiHandler {
    public const MAP = [
        'createUser' => CreateUser::class,
        'updateUser' => UpdateUser::class,
        'getUser' => GetUser::class,
        'getUsers' => GetUsers::class
    ];
}