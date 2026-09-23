<?php

namespace Database\Factories;

use App\Models\FeaturedCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeaturedCompanyFactory extends Factory
{
    protected $model = FeaturedCompany::class;

    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'logo_url' => null,
            'contract_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'description' => fake()->sentence(10),
        ];
    }
}
