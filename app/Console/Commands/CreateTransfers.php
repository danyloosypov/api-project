<?php

namespace App\Console\Commands;

use App\Models\Mongo\Flight;
use App\Models\MySql\Order;
use App\Models\MySql\Transfer;
use App\Models\MySql\User;
use App\Models\MySql\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Notifications\TransferCreatedNotification;

class CreateTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-transfers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $groupedOrders = Order::whereNull('id_transfers')
            ->where('arrival_date', '<=', Carbon::tomorrow())
            ->where('status', '!=', 'canceled')
            ->selectRaw('flight_num, DATE(arrival_date) as arrival_date, SUM(people_qty) as total_people, SUM(luggage_qty) as total_luggage')
            ->groupBy('flight_num', DB::raw('DATE(arrival_date)'))
            ->get();

        foreach ($groupedOrders as $group) {
            $arrivalDate = Carbon::parse($group->arrival_date)->toDateString();

            $flights = Flight::where('flight_date', $arrivalDate)->get();

            $flight = $flights->first(function ($flight) use ($group) {
                return isset($flight->flight['iata']) && $flight->flight['iata'] === $group->flight_num;
            });

            if (!$flight) {
                continue;
            }

            $scheduled = Carbon::parse($flight->arrival['scheduled']);

            $takenTransfers = Transfer::whereBetween('date', [
                $scheduled->copy()->subMinutes(30),
                $scheduled->copy()->addMinutes(90),
            ])->get();

            $takenVehicleIds = $takenTransfers->pluck('vehicle_id')->filter()->unique()->toArray();
            $takenDriverIds = $takenTransfers->pluck('driver_id')->filter()->unique()->toArray();

            $availableVehicles = Vehicle::whereNotIn('id', $takenVehicleIds)->get();

            $availableDrivers = User::whereNotIn('id', $takenDriverIds)
                ->where('role_id', 3)
                ->get();

            $orders = Order::where('flight_num', $group->flight_num)
                ->whereNull('id_transfers')
                ->where('arrival_date', '<=', Carbon::tomorrow())
                ->where('status', '!=', 'canceled')
                ->get();

            foreach ($orders as $order) {
                $vehicle = $availableVehicles->firstWhere(function ($v) use ($order) {
                    return $v->people_qty >= $order->people_qty &&
                        $v->luggage_qty >= $order->luggage_qty;
                });

                $driver = $availableDrivers->first();

                if (!$vehicle || !$driver) {
                    // No more available vehicles or drivers
                    continue;
                }

                $transferId = Transfer::create([
                    'driver_id' => $driver->id,
                    'vehicle_id' => $vehicle->id,
                    'luggage' => $order->luggage_qty,
                    'people_qty' => $order->people_qty,
                    'flight_num' => $group->flight_num,
                    'gate' => $flight->arrival['gate'] ?? '',
                    'status' => 'created',
                    'date' => $arrivalDate,
                ]);

                $order->update(['id_transfers' => $transferId]);

                $order->notify(new TransferCreatedNotification($order));

                $availableDrivers = $availableDrivers->reject(fn ($d) => $d->id === $driver->id);
                $availableVehicles = $availableVehicles->reject(fn ($v) => $v->id === $vehicle->id);
            }
        }
    }
}
