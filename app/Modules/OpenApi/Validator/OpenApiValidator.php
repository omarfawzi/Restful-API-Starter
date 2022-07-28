<?php

namespace App\Modules\OpenApi\Validator;

use App\Modules\OpenApi\Contexts\OpenApiContext;
use App\Modules\OpenApi\Errors\OpenApiError;
use App\Modules\OpenApi\Factories\OpenApiErrorFactory;
use App\Modules\OpenApi\Services\AuthenticationManager;
use Cache\Adapter\PHPArray\ArrayCachePool;
use cebe\openapi\spec\OpenApi;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\PathFinder;
use League\OpenAPIValidation\PSR7\SchemaFactory\JsonFactory;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiValidator
{
    private const CACHE_KEY = 'open-api';

    private ArrayCachePool $cachePool;

    private array $cache = [];

    public function __construct(
        private OpenApiErrorFactory $openApiErrorFactory,
        private AuthenticationManager $authenticationManager
    ) {
        $this->cachePool = new ArrayCachePool(null, $this->cache);
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return OpenApiContext
     * @throws OpenApiError
     */
    public function validateRequest(ServerRequestInterface $serverRequest): OpenApiContext
    {
        $schemaFactory = new JsonFactory($this->getOpenApiFile());

        $schema = $schemaFactory->createSchema();

        $address = $this->validateAndReturnAddress($serverRequest, $schema);

        if (false === empty($securityRequirements)) {
            $this->authenticationManager->authenticate($serverRequest, $address, $schema);
        }

        $validator = (new ValidatorBuilder())
            ->fromSchema($schema)
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
    public function validateResponse(ResponseInterface $response, OpenApiContext $context): void
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

    private function getOpenApiFile(): string
    {
        return Storage::get(config('app.open_api_file_path'));
    }

    private function validateAndReturnAddress(ServerRequestInterface $serverRequest, OpenApi $schema): OperationAddress
    {
        $pathFinder = new PathFinder($schema, $serverRequest->getUri(), $serverRequest->getMethod());

        $operationAddresses = $pathFinder->search();

        if (empty($operationAddresses)) {
            throw new InvalidArgumentException(
                "Operation with uri: {$serverRequest->getUri()} doesn't exist in the open api specs."
            );
        }

        if (count($operationAddresses) > 1) {
            throw new InvalidArgumentException(
                "Duplicate operations for uri: {$serverRequest->getUri()} exist in the open api specs."
            );
        }

        return $operationAddresses[0];
    }
}