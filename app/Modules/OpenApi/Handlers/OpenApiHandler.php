<?php

namespace App\Modules\OpenApi\Handlers;

use App\Modules\User\Actions\CreateUser;
use App\Modules\User\Actions\GetUser;
use App\Modules\User\Actions\GetUsers;
use App\Modules\User\Actions\UpdateUser;

final class OpenApiHandler {
    public const MAP = [
        'createUser' => CreateUser::class,
        'updateUser' => UpdateUser::class,
        'getUser' => GetUser::class,
        'getUsers' => GetUsers::class
    ];
}