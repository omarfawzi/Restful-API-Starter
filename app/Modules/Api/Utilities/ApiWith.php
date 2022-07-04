<?php

namespace App\Modules\Api\Utilities;

use Illuminate\Http\Request;

class ApiWith extends ApiQuery
{
    protected array $defaults = [];

    public static function createWithDefault(): static
    {
        $static = new static();

        foreach ($static->defaults as $default)
        {
            $static->bag[$default] = true;
        }

        return $static;
    }

    public function getQueryField(): string
    {
        return 'with';
    }

    public function extractQueryField(Request $request, string $field): ApiQuery
    {
        $relations = explode(self::DELIMITER, $request->query($field));

        foreach ($relations as $relation){
            $this->bag[$relation] = true;
        }

        return $this;
    }
}