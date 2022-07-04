<?php

namespace App\Modules\OpenApi\Contexts;

use cebe\openapi\spec\OpenApi;
use League\OpenAPIValidation\PSR7\OperationAddress;

final class OpenApiContext
{
    public function __construct(public OpenApi $openApi, public OperationAddress $operationAddress){}
}