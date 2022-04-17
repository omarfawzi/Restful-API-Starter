<?php

namespace App\Modules\Api\Utilities;

class ApiWith extends ApiQuery
{
    protected array $defaults = [];

    public static function createWithDefault(): self
    {
        $apiWith = new static();

        foreach ($apiWith->defaults as $default)
        {
            $apiWith->bag[$default] = true;
        }

        return $apiWith;
    }

    public function getQueryField(): string
    {
        return 'with';
    }
}