<?php

namespace App\Modules\Api\Utilities;

use Illuminate\Http\Request;

abstract class ApiQuery
{
    protected const DELIMITER = ',';

    protected array $bag = [];

    public function fromRequest(Request $request): self
    {
        $field = $this->getQueryField();

        if (false === $request->query->has($field)){
            return $this;
        }

        foreach ($request->query($field) as $key => $values){
            $this->bag[$key] = explode(self::DELIMITER, $values);
        }

        return $this;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->bag);
    }

    public function get(string $key): array
    {
        return $this->bag[$key];
    }

    abstract public function getQueryField(): string;
}