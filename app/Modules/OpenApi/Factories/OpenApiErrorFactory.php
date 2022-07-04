<?php

namespace App\Modules\OpenApi\Factories;

use App\Modules\OpenApi\Errors\OpenApiError;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;

class OpenApiErrorFactory
{
    public function make(ValidationFailed $exception): OpenApiError
    {
        $previous = $exception->getPrevious();

        if ($previous === null) {
            return new OpenApiError($exception->getMessage());
        }

        if ($previous instanceof SchemaMismatch) {
            if ($previous->dataBreadCrumb() === null) {
                return new OpenApiError($exception->getMessage());
            }

            $field = implode('.', $previous->dataBreadCrumb()->buildChain());

            if ($field === '') {
                $field = 'payload';
            }

            return new OpenApiError($exception->getMessage(), [$field => $previous->getMessage()]);
        }

        return new OpenApiError($previous->getMessage());
    }
}