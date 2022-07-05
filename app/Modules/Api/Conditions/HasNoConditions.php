<?php

namespace App\Modules\Api\Conditions;

trait HasNoConditions
{
    public function getConditions(): array
    {
        return [];
    }
}