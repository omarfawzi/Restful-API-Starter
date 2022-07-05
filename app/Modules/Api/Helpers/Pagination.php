<?php

namespace App\Modules\Api\Helpers;

use Spatie\LaravelData\Data;

class Pagination extends Data
{
    public ?string $sortDir = 'asc';

    public ?string $sortBy = 'id';

    public ?int $limit = 100;

    public ?string $cursor;
}