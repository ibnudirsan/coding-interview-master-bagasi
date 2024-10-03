<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\VoucherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
   Route::prefix('product')->group(function () {
        Route::name('product.')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::post('/', [ProductController::class, 'store']);
            Route::get('/{id}', [ProductController::class, 'find']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });
   });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('voucher')->group(function () {
        Route::get('/', [VoucherController::class, 'index']);
        Route::post('/', [VoucherController::class, 'store']);
        Route::get('/{id}', [VoucherController::class, 'find']);
        Route::put('/{id}', [VoucherController::class, 'update']);
        Route::delete('/{id}', [VoucherController::class, 'destroy']);
    });
});