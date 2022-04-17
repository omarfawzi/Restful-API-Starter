<?php

namespace App\Modules\Api\Dto;

use Illuminate\Http\Request;

class ApiRequest
{
    public static function fromRequest(Request $request): self
    {
        $dto = new static();

        foreach ($request->request->keys() as $key){
            if (property_exists($dto, $key)) {
                $dto->{$key} = $request->get($key);
            }
        }

        return $dto;
    }
}