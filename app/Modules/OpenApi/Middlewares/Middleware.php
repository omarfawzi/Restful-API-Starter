<?php

namespace App\Modules\OpenApi\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

interface Middleware
{
    public function handle(ServerRequestInterface $serverRequest): bool;
}