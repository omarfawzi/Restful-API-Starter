<?php

namespace App\Modules\User\Transformers;

use App\Modules\Api\Transformers\Transformer;
use App\Modules\Api\Utilities\ApiWith;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserTransformer extends Transformer
{
    /**
     * @param User $model
     * @param ApiWith $apiWith
     * @return array
     */
    public function transform(Model $model, ApiWith $apiWith): array
    {
        $result = [
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email
        ];

        if ($apiWith->has('token'))
        {
            $result['token'] = $model->token;
        }

        return $result;
    }
}