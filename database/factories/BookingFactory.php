<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'visitor_name' => fake()->name(),
            'booking_type' => fake()->randomElement(['استشارة', 'متابعة قضية', 'توقيع عقد', 'أخرى']),
            'preferred_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['قيد الانتظار', 'مؤكد', 'ملغي', 'مكتمل']),
            'users_id' => User::inRandomOrder()->value('id'),
            'clients_id' => Client::inRandomOrder()->value('id'),
        ];
    }
}
