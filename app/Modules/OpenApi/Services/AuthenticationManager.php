<?php

namespace App\Modules\OpenApi\Services;

use App\Modules\OpenApi\Factories\AuthenticationFactory;
use cebe\openapi\spec\OpenApi;
use Exception;
use Illuminate\Validation\UnauthorizedException;
use League\OpenAPIValidation\PSR7\PathFinder;
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
    public function authenticate(ServerRequestInterface $serverRequest, OpenApi $openApi): void
    {
        $pathFinder = new PathFinder($openApi, $serverRequest->getUri(), $serverRequest->getMethod());

        $operationAddresses = $pathFinder->search();

        if (empty($operationAddresses)) {
            throw new Exception("Operation with uri: {$serverRequest->getUri()} doesn't exist in the open api specs.");
        }

        if (count($operationAddresses) > 1) {
            throw new Exception(
                "Duplicate operations for uri: {$serverRequest->getUri()} exist in the open api specs."
            );
        }

        $specFinder = new SpecFinder($openApi);

        $securityRequirements = $specFinder->findSecuritySpecs($operationAddresses[0]);

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