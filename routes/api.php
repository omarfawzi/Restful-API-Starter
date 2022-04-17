<?php

use App\Modules\Api\Middlewares\ApiMiddleware;
use App\Modules\OpenApi\Middlewares\OpenApiMiddleware;
use App\Modules\User\Controllers\UserController;
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
        Route::post('/', [UserController::class, 'create']);
        Route::group(['middleware' => ApiMiddleware::class], function () {
            Route::get('/', [UserController::class, 'list']);
            Route::get('{id}', [UserController::class, 'show']);
            Route::patch('{id}', [UserController::class, 'update']);
        });
    });
});