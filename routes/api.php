<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
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
        Route::post('auth/register', [AuthController::class, 'createUser']);
        Route::get('auth/user', [AuthController::class, 'authUser']);
        Route::get('organizations/parents', [OrganizationController::class, 'parents']);
        Route::get('organizations/get_active', [OrganizationController::class, 'getActiveOrganizations']);
        Route::put('organizations/set_active/{id}', [OrganizationController::class, 'setActive']);
        Route::resource('organizations', OrganizationController::class);
        Route::get('rules/have', [UserRulesController::class, 'roles']);
        Route::get('rules/no', [UserRulesController::class, 'no_roles']);
        Route::resource('rules', UserRulesController::class);
        Route::resource('users', UserController::class);
    }
);



