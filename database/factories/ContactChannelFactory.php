<?php

namespace Database\Factories;

use App\Models\ContactChannel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactChannelFactory extends Factory
{
    protected $model = ContactChannel::class;

    public function definition(): array
    {
        return [
            'channel_type' => fake()->randomElement(['بريد إلكتروني', 'هاتف', 'واتساب', 'نموذج الموقع']),
            'message' => fake()->paragraph(),
            'status' => fake()->randomElement(['جديد', 'قيد المعالجة', 'مغلق']),
            'users_id' => User::inRandomOrder()->value('id'),
        ];
    }
}
