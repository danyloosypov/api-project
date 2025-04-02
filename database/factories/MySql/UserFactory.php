<?php

namespace Database\Factories\MySql;

use App\Models\MySql\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // Default password
            'phone' => $this->faker->phoneNumber(),
            'avatar' => $this->faker->imageUrl(100, 100, 'people'),
            'role_id' => rand(1, 4), // Assuming roles 1-4 exist (admin, manager, driver, user)
            'remember_token' => Str::random(10),
        ];
    }
}
