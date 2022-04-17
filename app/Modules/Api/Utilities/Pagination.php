<?php

namespace App\Modules\Api\Utilities;

use Illuminate\Http\Request;

class Pagination
{
    public string $sortDir = 'asc';

    public string $sortBy = 'id';

    public int $limit = 100;

    public ?string $cursor = null;

    public static function fromRequest(Request $request): self
    {
        $paginationParameter = new static();

        $paginationParameter->sortDir = $request->get('sortDir', $paginationParameter->sortDir);
        $paginationParameter->sortBy = $request->get('sortBy', $paginationParameter->sortBy);
        $paginationParameter->limit = $request->get('limit', $paginationParameter->limit);
        $paginationParameter->cursor = $request->get('cursor', $paginationParameter->cursor);

        return $paginationParameter;
    }
}