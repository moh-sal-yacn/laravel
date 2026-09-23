<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\CourtCase;
use App\Models\FinancialRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialRecordFactory extends Factory
{
    protected $model = FinancialRecord::class;

    public function definition(): array
    {
        return [
            'amount' => fake()->randomFloat(2, 100, 10000),
            'transaction_type' => fake()->randomElement(['دفعة عقد', 'رسوم قضية', 'مصروف', 'أخرى']),
            'transaction_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'notes' => fake()->optional()->sentence(),
            'cases_id' => CourtCase::inRandomOrder()->value('id'),
            'clients_id' => Client::inRandomOrder()->value('id'),
            'users_id' => User::factory()->lawyer(),
        ];
    }
}
