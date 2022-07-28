<?php

namespace App\Modules\OpenApi\Services;

use App\Modules\OpenApi\Factories\AuthenticationFactory;
use cebe\openapi\spec\OpenApi;
use Exception;
use Illuminate\Validation\UnauthorizedException;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\SpecFinder;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationManager
{
    public function __construct(private AuthenticationFactory $authenticationFactory)
    {
    }

    /**
     * @throws Exception
     */
    public function authenticate(
        ServerRequestInterface $serverRequest,
        OperationAddress $operationAddress,
        OpenApi $schema
    ): void {
        $specFinder = new SpecFinder($schema);

        $securityRequirements = $specFinder->findSecuritySpecs($operationAddress);

        if (empty($securityRequirements)) {
            return;
        }

        $successfulAuth = false;

        foreach ($securityRequirements as $securityRequirement) {
            foreach ((array)$securityRequirement->getSerializableData() as $securityMethod => $context) {
                $successfulAuth = $this->authenticationFactory->make($securityMethod, $context)->handle($serverRequest);
                if (true === $successfulAuth) {
                    return;
                }
            }
        }

        if (false === $successfulAuth) {
            throw new UnauthorizedException("Unauthorized access to a protected uri: {$serverRequest->getUri()}");
        }
    }
}