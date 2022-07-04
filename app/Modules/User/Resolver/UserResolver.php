<?php

namespace App\Modules\User\Resolver;

use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;

class UserResolver
{
    public array $cacheByEmail = [];

    public array $cacheById = [];
    
    public function __construct(private UserRepository $userRepository) {}

    /**
     * @param string $email
     * @return User|null
     */
    public function resolveByEmail(string $email): ?User
    {
        if (array_key_exists($email, $this->cacheByEmail)) {
            return $this->cacheByEmail[$email];
        }

        return $this->cacheByEmail[$email] = $this->userRepository->findByEmail($email);
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

        return $this->cacheById[$id] = $this->userRepository->findById($id);
    }
}
