<?php

namespace App\Modules\OpenApi\Validator;

use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Errors\OpenApiError;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiValidator
{
    private const CACHE_KEY = 'open-api';

    private ArrayCachePool $cachePool;

    private array $cache = [];

    public function __construct() {
        $this->cachePool = new ArrayCachePool(null, $this->cache);
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return OpenApiContext
     * @throws OpenApiError
     */
    public function validateRequest(ServerRequestInterface $serverRequest): OpenApiContext
    {
        $schema = $this->getOpenApiSchema();

        $validator = (new ValidatorBuilder())
            ->fromYaml($schema)
            ->setCache($this->cachePool)
            ->overrideCacheKey(self::CACHE_KEY)
            ->getServerRequestValidator();

        try {
            $address = $validator->validate($serverRequest);
        } catch (ValidationFailed $e) {
            throw $this->toOpenApiError($e);
        }

        return new OpenApiContext($validator->getSchema(), $address);
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @throws OpenApiError
     */
    public function validateResponse(OpenApiContext $context, ResponseInterface $response): void
    {
        $validator = (new ValidatorBuilder())
            ->fromSchema($context->openApi)
            ->setCache($this->cachePool)
            ->overrideCacheKey(self::CACHE_KEY)
            ->getResponseValidator();

        try {
            $validator->validate($context->operationAddress, $response);
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