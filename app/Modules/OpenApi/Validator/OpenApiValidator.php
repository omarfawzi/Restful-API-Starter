<?php

namespace App\Modules\OpenApi\Validator;

use App\Modules\OpenApi\Errors\OpenApiError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiValidator
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @param Request $request
     * @throws OpenApiError
     */
    public function validateRequest(ServerRequestInterface $serverRequest, Request $request): void
    {
        $address = new OperationAddress("/{$request->route()->uri}", strtolower($request->getMethod()));

        $schema = $this->getOpenApiSchema();

        $validator = (new ValidatorBuilder())->fromYaml($schema)->getRoutedRequestValidator();

        try {
            $validator->validate($address, $serverRequest);
        } catch (ValidationFailed $e) {
            throw $this->toOpenApiError($e);
        }
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @throws OpenApiError
     */
    public function validateResponse(Request $request, ResponseInterface $response): void
    {
        $address = new OperationAddress("/{$request->route()->uri}", strtolower($request->getMethod()));

        $schema = $this->getOpenApiSchema();

        $validator = (new ValidatorBuilder())->fromYaml($schema)->getResponseValidator();

        try {
            $validator->validate($address, $response);
        } catch (ValidationFailed $e) {
            throw $this->toOpenApiError($e);
        }
    }

    private function toOpenApiError(ValidationFailed $exception): OpenApiError
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

    private function getOpenApiSchema(): string
    {
        return Storage::get(config('app.open_api_file_path'));
    }
}