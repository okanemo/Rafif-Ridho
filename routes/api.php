<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\showMemberController;
use App\Http\Controllers\updateBalanceController;
use App\Http\Controllers\nabController;

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

//public
Route::post('/user/add', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);


//protected
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/v1/ib/listNAB' ,[nabController::class, 'index']);
    Route::Post('/v1/ib/updateTotalBalance', [nabController::class , 'update']);
    Route::get('/member', [showMemberController::class, 'index']);
    Route::post('/setNAB', [nabController::class, 'setNABVal']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/topup/{id}', [updateBalanceController::class, 'topup']);
    Route::post('/withdraw/{id}', [updateBalanceController::class, 'withdraw']);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
