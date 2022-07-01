<?php

namespace App\Modules\User\Actions;

use App\Modules\Api\Handlers\RequestHandler;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\Api\Utilities\ApiFilter;
use App\Modules\Api\Utilities\Pagination;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use App\Modules\User\With\UserWith;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class GetUsers extends RequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {}

    public function getConditions(): array
    {
        return [];
    }

    public function processRequest(Request $request): Response
    {
        $pagination = Pagination::fromRequest($request);

        $apiFilter = ApiFilter::fromRequest($request);

        $userWith = UserWith::fromRequest($request);

        $users = $this->service->list($pagination, $apiFilter);

        $result = $this->transformer->transformCollection($users, $pagination, $userWith);

        return ApiResponse::success($result);
    }
}
