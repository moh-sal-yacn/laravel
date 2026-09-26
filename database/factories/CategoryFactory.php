<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'قضايا مدنية',
            'قضايا جزائية',
            'قضايا تجارية',
            'قضايا عمالية',
            'قضايا أحوال شخصية',
            'قضايا عقارية',
            'قضايا إدارية',
            'قضايا دستورية',
            'قضايا أسرية',
            'قضايا إرث وتركات',
            'قضايا تنفيذ أحكام',
            'قضايا تحكيم',
        ];

        return [
            'category_name' => fake()->randomElement($categories),
            'categories_id' => null,
        ];
    }
}