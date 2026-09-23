<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'bio' => fake()->paragraph(),
            'office_address' => fake()->address(),
            'photo_url' => null,
            'license_number' => fake()->optional()->bothify('LIC-####'),
            'users_id' => User::factory()->lawyer(),
        ];
    }
}
