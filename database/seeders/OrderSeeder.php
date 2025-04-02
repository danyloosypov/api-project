<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MySql\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Order::create([
                'people_qty' => $faker->numberBetween(1, 10),
                'order_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'flight_num' => strtoupper(Str::random(6)),
                'status' => $faker->randomElement(['pending', 'confirmed', 'canceled']),
                'phone' => $faker->phoneNumber,
                'email' => $faker->safeEmail,
                'luggage_qty' => $faker->numberBetween(0, 5),
                'total' => $faker->randomFloat(2, 50, 1000),
                'arrival_date' => $faker->dateTimeBetween('now', '+1 year'),
                'comment' => $faker->optional()->sentence,
            ]);
        }
    }
}
