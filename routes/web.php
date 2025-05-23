<?php

use App\Models\Mongo\Flight;
use App\Models\MySql\Order;
use App\Models\MySql\Transfer;
use App\Models\MySql\User;
use App\Models\MySql\Vehicle;
use App\Notifications\TransferCreatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\TestRedisQueue;

Route::get('/clear_cache', function () {
    // Clear application cache
    Artisan::call('cache:clear');

    // Clear route cache
    Artisan::call('route:clear');

    // Clear config cache
    Artisan::call('config:clear');

    // Clear view cache
    Artisan::call('view:clear');

    return response()->json(['message' => 'All caches cleared successfully']);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/info', function () {
    phpinfo();
});

Route::get('/test', function () {
    dd(\App\Facades\AviationstackFacade::fetchAircraftTypesData(['offset' => 100]));
});

Route::get('/test-redis', function () {
    TestRedisQueue::dispatch();
    return 'Job dispatched!';
});

Route::get('/test_mongo', function () {
    $conn = \Illuminate\Support\Facades\DB::connection('mongodb');
    try {
        $conn->command(['ping' => 1]);
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

Route::get('/test-transfers', function () {
    $order = Order::find(35);
    $order->notify(new TransferCreatedNotification($order));
});
