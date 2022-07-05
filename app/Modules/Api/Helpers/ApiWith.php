<?php

namespace App\Modules\Api\Helpers;

use Illuminate\Http\Request;

class ApiWith extends ApiQuery
{
    public static function createWith(... $fields): static
    {
        $static = new static();

        foreach ($fields as $field)
        {
            $static->bag[$field] = true;
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