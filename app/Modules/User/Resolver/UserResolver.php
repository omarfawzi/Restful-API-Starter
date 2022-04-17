<?php

namespace App\Modules\User\Resolver;

use App\Modules\User\Models\User;

class UserResolver
{
    public array $cacheByEmail = [];

    public array $cacheById = [];

    /**
     * @param string $email
     * @return User|null
     */
    public function resolveByEmail(string $email): ?User
    {
        if (array_key_exists($email, $this->cacheByEmail)) {
            return $this->cacheByEmail[$email];
        }

        return $this->cacheByEmail[$email] = User::query()->where('email', $email)->first();
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function resolveById(int $id): ?User
    {
        if (array_key_exists($id, $this->cacheById)) {
            return $this->cacheById[$id];
        }

        return $this->cacheById[$id] = User::query()->where('id', $id)->first();
    }
}