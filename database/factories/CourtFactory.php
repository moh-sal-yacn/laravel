<?php

namespace Database\Factories;

use App\Models\Court;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourtFactory extends Factory
{
    protected $model = Court::class;

    public function definition(): array
    {
        return [
            'court_name' => fake()->randomElement(['محكمة الصلح', 'محكمة البداية', 'محكمة الاستئناف', 'محكمة النقض']),
            'jurisdiction' => fake()->randomElement(['مدني', 'جزائي', 'تجاري', 'أحوال شخصية']),
            'location' => fake()->city(),
        ];
    }
}
