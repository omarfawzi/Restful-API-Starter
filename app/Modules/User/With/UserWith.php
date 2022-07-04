<?php

namespace App\Modules\User\With;

use App\Modules\Api\Utilities\ApiWith;

class UserWith extends ApiWith
{
    protected array $defaults = ['token'];
}