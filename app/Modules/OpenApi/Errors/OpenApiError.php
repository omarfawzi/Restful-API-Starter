<?php

namespace App\Modules\OpenApi\Errors;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class OpenApiError extends Exception
{
    public function __construct(
        string $message = "",
        private array $errors = [],
        int $code = Response::HTTP_BAD_REQUEST,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}