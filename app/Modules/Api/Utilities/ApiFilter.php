<?php

namespace App\Modules\Api\Utilities;

class ApiFilter extends ApiQuery
{
    public function getQueryField(): string
    {
        return 'filter';
    }
}