<?php

namespace App\Modules\User\Repositories;

use App\Modules\Api\Repositories\BaseRepository;
use App\Modules\Api\Utilities\ApiFilter;
use App\Modules\Api\Utilities\Pagination;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository
{
    public function create(string $name, string $email, string $password): User|Model
    {
        return User::query()->create(['email' => $email, 'name' => $name, 'password' => bcrypt($password)]);
    }

    public function update(User $user, string $name, string $email = null, string $password = null): User
    {
        $fields = ['name' => $name, 'email' => $email, 'password' => $password];

        $user->update(array_filter($fields));

        return $user;
    }

    public function get(Pagination $pagination, ApiFilter $filter): Collection
    {
        $query = User::query()->when($filter->has('name'), function (Builder $query) use ($filter) {
            $query->whereIn('name', $filter->get('name'));
        })->when($filter->has('email'), function (Builder $query) use ($filter) {
            $query->whereIn('email', $filter->get('email'));
        });

        return $this->paginate($query, $pagination)->get();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::query()->where('id', $id)->first();
    }
}
