<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    RegisterController, LoginController, MeController, LogoutController
};
use App\Http\Controllers\AuthController;
use App\Http\Controllers\showMemberController;
use App\Http\Controllers\updateBalanceController;
use App\Http\Controllers\nabController;



//public
Route::post('/v1/user/add', RegisterController::class); //invokable
Route::post('/v1/user/login', LoginController::class); //invokable
// Route::post('/user/add', [AuthController::class, 'register']);
// Route::post('/user/login', [AuthController::class, 'login']);

//protected
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/v1/ib/member', MeController::class);
    Route::get('/v1/ib/listNAB' ,[nabController::class, 'index']);
    Route::Post('/v1/ib/updateTotalBalance', [nabController::class , 'update']);
    // Route::get('v1/ib/member', [showMemberController::class, 'index']);
    Route::post('v1/ib/setNAB', [nabController::class, 'setNABVal']);
    // Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('v1/user/logout', LogoutController::class);
    Route::post('v1/ib/topup/{id}', [updateBalanceController::class, 'topup']);
    Route::post('v1/ib/withdraw/{id}', [updateBalanceController::class, 'withdraw']);
    Route::post('v1/user/logout', [AuthController::class, 'logout']);
});

// Route::group(['middleware' => ['auth:sanctum']], function () {
//     Route::get('/v1/ib/listNAB' ,[nabController::class, 'index']);
//     Route::Post('/v1/ib/updateTotalBalance', [nabController::class , 'update']);
//     Route::get('/member', [showMemberController::class, 'index']);
//     Route::post('/setNAB', [nabController::class, 'setNABVal']);
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::post('/topup/{id}', [updateBalanceController::class, 'topup']);
//     Route::post('/withdraw/{id}', [updateBalanceController::class, 'withdraw']);
//     Route::post('/user/logout', [AuthController::class, 'logout']);
// });


