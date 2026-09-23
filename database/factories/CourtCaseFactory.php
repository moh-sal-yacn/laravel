<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Client;
use App\Models\Court;
use App\Models\CourtCase;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourtCaseFactory extends Factory
{
    protected $model = CourtCase::class;

    public function definition(): array
    {
        return [
            'case_number' => 'C-'.fake()->unique()->numerify('#####'),
            'case_title' => fake()->sentence(4),
            'case_status' => fake()->randomElement(['قيد النظر', 'مؤجلة', 'منتهية']),
            'opened_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'description' => fake()->paragraph(),
            'clients_id' => Client::factory(),
            'categories_id' => Category::inRandomOrder()->value('id'),
            'courts_id' => Court::factory(),
        ];
    }
}
