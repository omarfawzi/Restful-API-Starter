<?php

namespace App\Modules\Api\Utilities;

use Illuminate\Http\Request;

abstract class ApiQuery
{
    protected const DELIMITER = ',';

    protected array $bag = [];

    public static function fromRequest(Request $request): static
    {
        $static = new static();

        $field = $static->getQueryField();

        if (false === $request->query->has($field)){
            return $static;
        }

        return $static->extractQueryField($request, $field);
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

    abstract public function extractQueryField(Request $request, string $field): self;
}