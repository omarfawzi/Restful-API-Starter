<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Api\Utilities\ApiWith;
use App\Modules\Api\Utilities\Pagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Transformer
{
    abstract public function transform(Model $model, ApiWith $apiWith): array;

    public function transformCollection(Collection $collection, Pagination $paginationParameter, ApiWith $apiWith): array
    {
        $result = ['entities' => [], 'cursor' => null];

        foreach ($collection as $model)
        {
            $result['entities'][] = $this->transform($model, $apiWith);
        }

        if ($collection->isNotEmpty()){
            $result['cursor'] = base64_encode($collection->last()->{$paginationParameter->sortBy});
        }

        return $result;
    }
}