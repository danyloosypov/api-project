<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MySql\Transfer;
use App\Models\MySql\User;
use App\Models\MySql\Vehicle;
use Faker\Factory as Faker;

class TransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $drivers = User::pluck('id')->toArray();
        $vehicles = Vehicle::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            Transfer::create([
                'driver_id'   => $faker->randomElement($drivers),
                'vehicle_id'  => $faker->randomElement($vehicles),
                'luggage'     => $faker->numberBetween(0, 5),
                'people_qty'  => $faker->numberBetween(1, 8),
                'flight_num'  => strtoupper($faker->bothify('??###')),
                'pickup'      => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
                'unload'      => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
                'gate'        => $faker->numberBetween(1, 50),
                'destination' => $faker->city,
                'status'      => $faker->randomElement(['pending', 'completed', 'canceled']),
                'comment'     => $faker->sentence(),
            ]);
        }
    }
}
