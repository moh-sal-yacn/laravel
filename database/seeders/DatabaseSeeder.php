<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class, // 1. roles + permissions
            UserSeeder::class,           // 2. users (needs roles)
            ClientSeeder::class,         // 3. clients (needs users)
            CourtCategorySeeder::class,  // 4. courts + categories
            CaseSeeder::class,           // 5. cases + participants (needs clients, courts, categories, users)
            OperationsSeeder::class,     // 6. everything else (needs cases, clients, users)
        ]);
    }
}
