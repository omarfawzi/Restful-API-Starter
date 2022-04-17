<?php

namespace App\Modules\Api\Utilities;

class ApiWith extends ApiQuery
{
    protected array $defaults = [];

    public function createWithDefault(): self
    {
        foreach ($this->defaults as $default)
        {
            $this->bag[$default] = true;
        }

        return $this;
    }

    public function getQueryField(): string
    {
        return 'with';
    }
}