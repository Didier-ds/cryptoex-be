<?php

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ForgetPasswordController;
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

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Email verification message sent']);
    })->name('verification.send');

    Route::post('/password-reset/request', [ForgetPasswordController::class, "requestReset"]);
    Route::post('/password/reset', [ForgetPasswordController::class, "passwordReset"]);

    Route::get('/cards', [CardController::class, "index"]);
    Route::get('/cards/{id}', [CardController::class, "show"]);
});


//  --------------------------- Private Route ------------------------------- //

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('users/user', [LoginController::class, "fetchUserBYToken"]);
    Route::put('/users/user/profile', [UsersControllers::class, "updateProfile"]);
    Route::post('/register/admin', [AdminsController::class, "createAdmin"]);

    Route::post('/cards', [CardController::class, "store"]);
    Route::put('/cards/{id}', [CardController::class, "update"]);
    Route::delete('/cards/{id}', [CardController::class, "destroy"]);
});
