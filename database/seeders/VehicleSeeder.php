<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MySql\Vehicle;
use Faker\Factory as Faker;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Vehicle::create([
                'model' => $faker->company . ' ' . $faker->word,
                'license' => strtoupper($faker->bothify('??-###-??')),
                'luggage_qty' => rand(2, 5),
                'people_qty' => rand(4, 11),
            ]);
        }
    }
}
