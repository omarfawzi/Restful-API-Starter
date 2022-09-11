<?php

namespace App\Modules\User\Requests;

use App\Modules\Api\Helpers\ApiWith;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\Api\Validator\Validator;
use App\Modules\OpenApi\Handlers\RequestHandler;
use App\Modules\User\Conditions\UserDoesExist;
use App\Modules\User\Resolver\UserResolver;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class GetUser extends RequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service,
        private UserResolver $userResolver
    ) {}

    public function __invoke(Request $request): Response
    {
        Validator::validate($request, [
            new UserDoesExist(
                $this->userResolver,
                $this->getPathParameterAsInteger('id')
            ),
        ]);

        $user = $this->service->find($this->getPathParameterAsInteger('id'));

        $apiWith = ApiWith::from($request);

        $result = $this->transformer->transform($user, $apiWith);

        return ApiResponse::success($result);
    }
}
