<?php

namespace App\Modules\Api\Utilities;

use Illuminate\Http\Request;

class Pagination
{
    public string $sortDir = 'asc';

    public string $sortBy = 'id';

    public int $limit = 100;

    public ?string $cursor = null;

    public static function fromRequest(Request $request): static
    {
        $static = new static();
        $static->sortDir = $request->get('sortDir', $static->sortDir);
        $static->sortBy = $request->get('sortBy', $static->sortBy);
        $static->limit = $request->get('limit', $static->limit);
        $static->cursor = $request->get('cursor', $static->cursor);

        return $static;
    }
}