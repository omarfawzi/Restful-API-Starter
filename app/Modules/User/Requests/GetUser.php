<?php

namespace App\Modules\User\Requests;

use App\Modules\Api\Handlers\ApiRequestHandler;
use App\Modules\Api\Helpers\ApiWith;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\User\Conditions\UserDoesExist;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class GetUser extends ApiRequestHandler
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

        $apiWith = ApiWith::from($request);

        $result = $this->transformer->transform($user, $apiWith);

        return ApiResponse::success($result);
    }
}
