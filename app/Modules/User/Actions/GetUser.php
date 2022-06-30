<?php

namespace App\Modules\User\Actions;

use App\Modules\Api\Handlers\RequestHandler;
use App\Modules\OpenApi\Utilities\OpenApiResponse;
use App\Modules\User\Conditions\UserDoesExist;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use App\Modules\User\With\UserWith;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class GetUser extends RequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {}

    public function getConditions(): array
    {
        return [
            new UserDoesExist($this->getPathParameterAsInteger('id'))
        ];
    }

    public function processRequest(Request $request): Response
    {
        $user = $this->service->find($this->getPathParameterAsInteger('id'));

        $userWith = UserWith::fromRequest($request);

        $result = $this->transformer->transform($user, $userWith);

        return OpenApiResponse::success($result);
    }
}
