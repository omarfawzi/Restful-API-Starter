<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Api\Helpers\ApiWith;
use App\Modules\Api\Helpers\Pagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Transformer
{
    abstract public function transform(Model $model, ApiWith $apiWith): array;

    public function transformCollection(Collection $collection, Pagination $pagination, ApiWith $apiWith): array
    {
        $result = ['entities' => [], 'cursor' => null];

        foreach ($collection as $model)
        {
            $result['entities'][] = $this->transform($model, $apiWith);
        }

        if ($collection->isNotEmpty()){
            $result['cursor'] = base64_encode($collection->last()->{$pagination->sortBy});
        }

        return $result;
    }
}