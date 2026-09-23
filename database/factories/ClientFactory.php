<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'client_kind' => fake()->randomElement(['individual', 'company']),
            'national_id' => fake()->unique()->numerify('##########'),
            'users_id' => User::factory()->client(),
        ];
    }
}
