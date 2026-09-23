<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\CourtCase;
use App\Models\ServiceRating;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceRatingFactory extends Factory
{
    protected $model = ServiceRating::class;

    public function definition(): array
    {
        return [
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->optional()->sentence(),
            'clients_id' => Client::inRandomOrder()->value('id'),
            'categories_id' => Category::inRandomOrder()->value('id'),
            'cases_id' => CourtCase::inRandomOrder()->value('id'),
            'bookings_id' => Booking::inRandomOrder()->value('id'),
        ];
    }
}
