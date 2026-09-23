<?php

namespace Database\Factories;

use App\Models\CaseSession;
use App\Models\CourtCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CaseSessionFactory extends Factory
{
    protected $model = CaseSession::class;

    public function definition(): array
    {
        return [
            'session_date' => fake()->dateTimeBetween('-6 months', '+3 months'),
            'session_notes' => fake()->paragraph(),
            'next_session_date' => fake()->optional()->dateTimeBetween('now', '+2 months'),
            'cases_id' => CourtCase::factory(),
            'users_id' => User::factory()->lawyer(),
        ];
    }
}
