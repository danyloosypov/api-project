<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransfersController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\FlightsController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

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

Route::get('/flights', [FlightsController::class, 'getFlights']);
Route::get('/flight-routes', [FlightsController::class, 'getFlightRoutes']);
Route::get('/airports', [FlightsController::class, 'getAirports']);
Route::get('/airlines', [FlightsController::class, 'getAirlines']);
Route::get('/airplanes', [FlightsController::class, 'getAirplanes']);
Route::get('/aircraft-types', [FlightsController::class, 'getAircraftTypes']);
Route::get('/cities', [FlightsController::class, 'getCities']);
Route::get('/countries', [FlightsController::class, 'getCountries']);
Route::get('/flight-schedules', [FlightsController::class, 'getFlightSchedules']);
Route::get('/flight-future-schedules', [FlightsController::class, 'getFlightFutureSchedules']);

Route::get('/test', [\App\Http\Controllers\TestController::class, 'test']);
