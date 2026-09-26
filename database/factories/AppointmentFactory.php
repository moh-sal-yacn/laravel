<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'appointment_date' => fake()->dateTimeBetween('-1 month', '+2 months'),
            'status'           => fake()->randomElement(['مجدول', 'مكتمل', 'ملغي']),
            'bookings_id'      => Booking::inRandomOrder()->value('id'), // قد يكون null
            'users_id'         => User::where('user_type', 'lawyer')->inRandomOrder()->value('id'),
            'clients_id'       => Client::inRandomOrder()->value('id'),
        ];
    }
}