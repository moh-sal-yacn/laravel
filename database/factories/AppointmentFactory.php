<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'appointment_date' => fake()->dateTimeBetween('now', '+2 months'),
            'status' => fake()->randomElement(['مجدول', 'مكتمل', 'ملغي']),
            'users_id' => User::factory()->lawyer(),
            'clients_id' => Client::factory(),
        ];
    }
}
