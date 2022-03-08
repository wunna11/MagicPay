<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('Api')->group(function () {
    // Route::get('/tst', [PageController::class, 'index']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/profile', [PageController::class, 'profile']);
        Route::post('/profile/logout', [AuthController::class, 'logout']);

        Route::get('/transaction', [PageController::class, 'transaction']);
        Route::get('/transaction/{trx_id}', [PageController::class, 'transactionDetail']);

        Route::get('/notification', [PageController::class, 'notification']);
        Route::get('/notification/{id}', [PageController::class, 'notificationDetail']);

        Route::get('/to-account-verify', [PageController::class, 'toAccountVerify']);

        Route::get('/transfer/confirm', [PageController::class, 'transferConfirm']);
        Route::post('/transfer/complete', [PageController::class, 'transferComplete']);

        Route::get('/scan-and-pay-form', [PageController::class, 'scanAndPayForm']);
        Route::get('/scan-and-pay/confirm', [PageController::class, 'scanAndPayConfirm']);
        Route::post('/scan-and-pay/complete', [PageController::class, 'scanAndPayComplete']);
    });
});
