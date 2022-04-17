<?php

namespace App\Modules\User\Repositories;

use App\Modules\Api\Utilities\ApiFilter;
use App\Modules\Api\Utilities\Pagination;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository
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
        $cursor = $pagination->cursor ? base64_decode($pagination->cursor) : null;

        $query = User::query();

        if (null !== $cursor){
            $query->where($pagination->sortBy, $pagination->sortDir === 'asc' ? '>' : '<', $cursor);
        }

        if ($filter->has('name')) {
            $query->whereIn('name', $filter->get('name'));
        }

        if ($filter->has('email')) {
            $query->whereIn('email', $filter->get('email'));
        }

        return $query->limit($pagination->limit)
            ->orderBy($pagination->sortBy, $pagination->sortDir)
            ->get();
    }
}