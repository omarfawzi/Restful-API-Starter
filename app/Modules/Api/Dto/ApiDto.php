<?php

namespace App\Modules\Api\Dto;

use Illuminate\Http\Request;

class ApiDto
{
    public static function fromRequest(Request $request): static
    {
        $static = new static();

        foreach ($request->request->keys() as $key){
            if (property_exists($static, $key)) {
                $static->{$key} = $request->get($key);
            }
        }

        return $static;
    }
}