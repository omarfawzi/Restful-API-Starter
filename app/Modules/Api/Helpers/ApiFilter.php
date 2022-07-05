<?php

namespace App\Modules\Api\Helpers;

use Illuminate\Http\Request;

class ApiFilter extends ApiQuery
{
    public function getQueryField(): string
    {
        return 'filter';
    }

    public function extractQueryField(Request $request, string $field): ApiQuery
    {
        foreach ($request->query($field) as $key => $values){
            $this->bag[$key] = explode(self::DELIMITER, $values);
        }

        return $this;
    }
}