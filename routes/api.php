<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\BtcRateController;
use App\Http\Controllers\CardletController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UsersControllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//  ------------------------ Public Routes ------------------------------- //
Route::prefix('v1')->group(function () {
    Route::post('/register', [RegisterController::class, "register"]);
    Route::post('/login', [LoginController::class, "login"]);
    Route::post('/manager-login', [LoginController::class, "login"]);

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Email verification message sent']);
    })->name('verification.send');

    Route::post('/password-reset/request', [ForgetPasswordController::class, "requestReset"]);
    Route::post('/password/reset', [ForgetPasswordController::class, "passwordReset"]);
    Route::get('/cardnames', [CardController::class, "names"]);
    Route::get('/btc-rate', [BtcRateController::class, "index"]);
    Route::post('/cardnames', [CardController::class, "putName"]);
    Route::get('/cards', [CardController::class, "index"]);
    Route::get('/banks', [BankController::class, "getAllBanks"]);
    Route::get('/cards/{id}', [CardController::class, "show"]);
    Route::post('/vet-bank', [BankController::class, "velidateBank"]);
    Route::post('/owner-login', [LoginController::class, "ownerLogin"]);
});


//  --------------------------- Private Route ------------------------------- //

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('users/user', [LoginController::class, "fetchUserBYToken"]);
    Route::put('/users/user/profile', [UsersControllers::class, "updateProfile"]);
    Route::post('create-admin', [RegisterController::class, "createAdmin"]);


    Route::put('/users/account', [AccountController::class, 'updateAccount']);

    Route::post('/cards', [CardController::class, "store"]);

    Route::put('/cards/{id}', [CardController::class, "update"]);
    Route::patch('/cards/{uuid}', [CardController::class, "cardRateChange"]);
    Route::delete('/cards/{id}', [CardController::class, "destroy"]);

    Route::post('/payment/proof', [PaymentProofController::class, 'store']);


    /**
     * for card owners
     */
    Route::get('/users/cardlets', [CardletController::class, 'userCardlets']);
    Route::post('/users/cardlets-make/{Carduuid}', [CardletController::class, 'store']);  // To create cardlet
    Route::patch('/users/cardlets/{uuid}', [CardletController::class, 'updateCardlet']);


    /**
     * for Admins
     */

    Route::get('/admin/all-users', [AdminController::class, 'allUsers']);
    Route::get('/admin/all-users/{uuid}', [AdminController::class, 'oneUser']);
    Route::put('/btc-rate', [BtcRateController::class, "updateRate"]);
    Route::post('/btc-rate', [BtcRateController::class, "store"]);
    Route::get('/users/cardlets-all', [CardletController::class, 'index']);
    Route::get('/users/cardlets-status', [CardletController::class, 'cardletsBySatus']);
    Route::patch('/users/cardlets-status/{uuid}', [CardletController::class, 'cardletStatusChaneg']); // cardlet UUid
});
