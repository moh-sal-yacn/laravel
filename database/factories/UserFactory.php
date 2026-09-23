<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => fake()->numerify('05########'),
            'user_type' => fake()->randomElement(['admin', 'lawyer', 'client', 'staff']),
            'is_active' => true,
        ];
    }

    // Attach one of the already-seeded roles instead of creating new ones,
    // to avoid duplicate role_name unique-constraint violations.
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $roleId = Role::inRandomOrder()->value('id');
            if ($roleId) {
                $user->roles()->syncWithoutDetaching([$roleId]);
            }
        });
    }

    public function admin(): static
    {
        return $this->state(fn () => ['user_type' => 'admin']);
    }

    public function lawyer(): static
    {
        return $this->state(fn () => ['user_type' => 'lawyer']);
    }

    public function client(): static
    {
        return $this->state(fn () => ['user_type' => 'client']);
    }
}
