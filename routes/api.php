<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRulesController;

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
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(
    function () {
        //register
        Route::post('auth/register', [AuthController::class, 'createUser']);
        //user malumotlari
        Route::get('auth/user', [AuthController::class, 'authUser']);
        //organizatsiyalar
        Route::get('organizations/parents', [OrganizationController::class, 'parents']);
        Route::get('organizations/get_active', [OrganizationController::class, 'getActiveOrganizations']);
        Route::put('organizations/set_active/{id}', [OrganizationController::class, 'setActive']);
        Route::resource('organizations', OrganizationController::class);
        //lavozimlar
        Route::get('positions/get_active', [PositionController::class, 'getActivePositions']);
        Route::put('positions/set_active/{id}', [PositionController::class, 'setActive']);
        Route::resource('positions', PositionController::class);
        //rullar
        Route::get('rules/have', [UserRulesController::class, 'roles']);
        Route::get('rules/no', [UserRulesController::class, 'no_roles']);
        Route::post('rules/add', [UserRulesController::class, 'add']);
        Route::post('rules/remove', [UserRulesController::class, 'remove']);
        Route::resource('rules', UserRulesController::class);
        //userlar
        Route::resource('users', UserController::class);
    }
);



