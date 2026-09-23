<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'مدير النظام',
            'email' => 'admin@lawfirm.test',
        ]);
        $admin->roles()->sync([Role::where('role_name', 'Admin')->value('id')]);

        $lawyers = User::factory()->lawyer()->count(5)->create();
        $lawyerRoleId = Role::where('role_name', 'Lawyer')->value('id');
        $lawyers->each(fn ($u) => $u->roles()->sync([$lawyerRoleId]));

        // 10 additional generic users (mixed roles handled by the factory itself)
        User::factory()->count(10)->create();
    }
}
