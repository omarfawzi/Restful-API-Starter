<?php

namespace App\Modules\OpenApi\Validator;

use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Factories\OpenApiErrorFactory;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Illuminate\Support\Facades\Storage;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiValidator
{
    private const CACHE_KEY = 'open-api';

    private ArrayCachePool $cachePool;

    private array $cache = [];

    public function __construct(private OpenApiErrorFactory $openApiErrorFactory)
    {
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
            throw $this->openApiErrorFactory->make($e);
        }

        return new OpenApiContext($validator->getSchema(), $address);
    }

    /**
     * @param OpenApiContext $context
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
            throw $this->openApiErrorFactory->make($e);
        }
    }

    private function getOpenApiSchema(): string
    {
        return Storage::get(config('app.open_api_file_path'));
    }
}