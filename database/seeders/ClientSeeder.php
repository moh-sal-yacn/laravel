<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Turn a handful of existing "client" type users into Client records,
        // then top up with fresh factory-created client users if needed.
        $clientUsers = User::where('user_type', 'client')->get();

        foreach ($clientUsers as $user) {
            Client::firstOrCreate(
                ['users_id' => $user->id],
                [
                    'client_kind' => fake()->randomElement(['individual', 'company']),
                    'national_id' => fake()->unique()->numerify('##########'),
                ]
            );
        }

        if (Client::count() < 8) {
            Client::factory()->count(8 - Client::count())->create();
        }
    }
}
