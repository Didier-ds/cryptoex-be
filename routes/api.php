<?php

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UsersControllers;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//  ------------------------ Public Routes ------------------------------- //
Route::prefix('v1')->group(function () {
    Route::post('/register', [RegisterController::class, "register"]);
    Route::post('/login', [LoginController::class, "login"]);
    Route::post('/login', [LoginController::class, "login"]);

    route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response(['message' => 'Email verification message sent'], 200);
    });
});


//  --------------------------- Private Route ------------------------------- //

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('users/user', [LoginController::class, "fetchUserBYToken"]);
    Route::put('/users/user/profile', [UsersControllers::class, "updateProfile"]);

    Route::post('/register/admin', [AdminsController::class, "createAdmin"]);
});
