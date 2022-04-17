<?php

namespace App\Modules\Api\Utilities;

use Illuminate\Http\Request;

abstract class ApiQuery
{
    protected const DELIMITER = ',';

    protected array $bag = [];

    public static function fromRequest(Request $request): self
    {
        $apiQuery = new static();

        $field = $apiQuery->getQueryField();

        if (false === $request->query->has($field)){
            return $apiQuery;
        }

        foreach ($request->query($field) as $key => $values){
            $apiQuery->bag[$key] = explode(self::DELIMITER, $values);
        }

        return $apiQuery;
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