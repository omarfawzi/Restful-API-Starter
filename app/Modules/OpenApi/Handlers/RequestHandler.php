<?php

namespace App\Modules\OpenApi\Handlers;

use Exception;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

abstract class RequestHandler
{
    private array $pathParameterBag;

    public function setPathParameterBag(array $pathParameterBag): void
    {
        $this->pathParameterBag = $pathParameterBag;
    }

    /**
     * @throws Exception
     */
    public function getPathParameterAsInteger(string $parameter): int
    {
        return (int) $this->getPathParameter($parameter);
    }

    /**
     * @throws Exception
     */
    public function getPathParameterAsString(string $parameter): string
    {
        return (string) $this->getPathParameter($parameter);
    }

    /**
     * @throws Exception
     */
    private function getPathParameter(string $parameter): string|int
    {
        if (false === array_key_exists($parameter, $this->pathParameterBag)){
            throw new Exception("Path parameter : $parameter not found within the current request path.");
        }

        return $this->pathParameterBag[$parameter];
    }

    abstract public function __invoke(Request $request): Response;
}