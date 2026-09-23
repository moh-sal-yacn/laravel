<?php

namespace Database\Factories;

use App\Models\CourtCase;
use App\Models\LegalPrecedent;
use Illuminate\Database\Eloquent\Factories\Factory;

class LegalPrecedentFactory extends Factory
{
    protected $model = LegalPrecedent::class;

    public function definition(): array
    {
        return [
            'source' => fake()->randomElement(['محكمة النقض', 'محكمة الاستئناف', 'محكمة عليا', 'أخرى']),
            'title' => fake()->sentence(5),
            'summary' => fake()->paragraph(),
            'external_link' => fake()->optional()->url(),
            'ruling_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'cases_id' => CourtCase::inRandomOrder()->value('id'),
        ];
    }
}
