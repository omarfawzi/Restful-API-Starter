<?php

use App\Modules\Api\Middlewares\ApiMiddleware;
use App\Modules\OpenApi\Middlewares\OpenApiMiddleware;
use App\Modules\User\Actions\CreateUser;
use App\Modules\User\Actions\GetUser;
use App\Modules\User\Actions\GetUsers;
use App\Modules\User\Actions\UpdateUser;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => OpenApiMiddleware::class], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::post('/', CreateUser::class);
        Route::group(['middleware' => ApiMiddleware::class], function () {
            Route::get('/', GetUsers::class);
            Route::get('{id}', GetUser::class);
            Route::patch('{id}', UpdateUser::class);
        });
    });
});