<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransfersController;
use App\Http\Controllers\Api\OrdersController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('transfers')->group(function () {
    Route::get('/', [TransfersController::class, 'index']);
    Route::get('/{id}', [TransfersController::class, 'show']);
    Route::post('/', [TransfersController::class, 'store']);
    Route::put('/{id}', [TransfersController::class, 'update']);
    Route::delete('/{id}', [TransfersController::class, 'destroy']);
});

Route::prefix('orders')->group(function () {
    Route::get('/', [OrdersController::class, 'index']);
    Route::get('/{id}', [OrdersController::class, 'show']);
    Route::post('/', [OrdersController::class, 'store']);
    Route::put('/{id}', [OrdersController::class, 'update']);
    Route::delete('/{id}', [OrdersController::class, 'destroy']);
});

Route::get('/test', [\App\Http\Controllers\TestController::class, 'test']);
