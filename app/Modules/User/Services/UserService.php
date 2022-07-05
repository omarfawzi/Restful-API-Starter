<?php

namespace App\Modules\User\Services;

use App\Modules\Api\Helpers\ApiFilter;
use App\Modules\Api\Helpers\Pagination;
use App\Modules\User\Dto\UpdateUserData;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Dto\CreateUserData;
use App\Modules\User\Resolver\UserResolver;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(private UserRepository $userRepository, private UserResolver $userResolver)
    {
    }

    public function create(CreateUserData $dto): User
    {
        $user = $this->userRepository->create($dto->name, $dto->email, $dto->password);

        $token = $user->createToken('user_token');

        $user->token = $token->plainTextToken;

        return $user;
    }

    public function update(int $id, UpdateUserData $dto): User
    {
        $user = $this->userResolver->resolveById($id);

        return $this->userRepository->update($user, $dto->name, $dto->email, $dto->password);
    }

    public function find(int $id): User
    {
        return $this->userResolver->resolveById($id);
    }

    public function list(Pagination $pagination, ApiFilter $filter): Collection
    {
        return $this->userRepository->get($pagination, $filter);
    }
}