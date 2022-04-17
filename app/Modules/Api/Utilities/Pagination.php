<?php

namespace App\Modules\Api\Utilities;

use Illuminate\Http\Request;

class Pagination
{
    public string $sortDir = 'asc';

    public string $sortBy = 'id';

    public int $limit = 100;

    public ?string $cursor = null;

    public function fromRequest(Request $request): self
    {
        $this->sortDir = $request->get('sortDir', $this->sortDir);
        $this->sortBy = $request->get('sortBy', $this->sortBy);
        $this->limit = $request->get('limit', $this->limit);
        $this->cursor = $request->get('cursor', $this->cursor);

        return $this;
    }
}