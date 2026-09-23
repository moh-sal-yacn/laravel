<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        return [
            'contract_type' => fake()->randomElement(['استشارة قانونية', 'تمثيل قضائي', 'صياغة عقود']),
            'contract_status' => fake()->randomElement(['نشط', 'منتهي', 'ملغي']),
            'parties' => fake()->company().' مقابل '.fake()->company(),
            'contract_value' => fake()->randomFloat(2, 500, 50000),
            'signed_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'clients_id' => Client::factory(),
            'users_id' => User::factory()->lawyer(),
        ];
    }
}
