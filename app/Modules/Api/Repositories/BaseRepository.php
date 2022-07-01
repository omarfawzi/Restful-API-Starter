<?php

namespace App\Modules\Api\Repositories;

use App\Modules\Api\Utilities\Pagination;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    protected function paginate(Builder $query, Pagination $pagination): Builder
    {
        $cursor = $pagination->cursor ? base64_decode($pagination->cursor) : null;

        if (null !== $cursor) {
            $query->where($pagination->sortBy, $pagination->sortDir === 'asc' ? '>' : '<', $cursor);
        }

        return $query->limit($pagination->limit)->orderBy($pagination->sortBy, $pagination->sortDir);
    }
}
