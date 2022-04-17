<?php

namespace App\Modules\Api\Dto;

use Illuminate\Http\Request;

class ApiDto
{
    public function fromRequest(Request $request): self
    {
        foreach ($request->request->keys() as $key){
            if (property_exists($this, $key)) {
                $this->{$key} = $request->get($key);
            }
        }

        return $this;
    }
}