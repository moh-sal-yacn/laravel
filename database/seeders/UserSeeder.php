<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ────── مدير النظام (حساب افتراضي) ──────
        $admin = User::factory()->admin()->create([
            'name'     => 'مدير النظام',
            'email'    => 'admin@lawfirm.test',
            'password' => bcrypt('password'), // الافتراضي
        ]);
        $admin->roles()->sync([
            Role::where('role_name', 'مدير النظام')->value('id'),
        ]);

        // ────── المحامون (5 محامين) ──────
        $lawyers = User::factory()->lawyer()->count(5)->create();
        $lawyerRoleId = Role::where('role_name', 'محامي')->value('id');
        $lawyers->each(fn ($u) => $u->roles()->sync([$lawyerRoleId]));

        // ────── الموظفون الإداريون (2) ──────
        $staff = User::factory()->count(2)->create(['user_type' => 'staff']);
        $staffRoleId = Role::where('role_name', 'موظف إداري')->value('id');
        $staff->each(fn ($u) => $u->roles()->sync([$staffRoleId]));

        // ────── مستخدمون متنوعون (5 - موكلون ومحامون إضافيون) ──────
        // ملاحظة: المستخدمون من نوع "client" يُنشأون لاحقاً في ClientSeeder
        User::factory()->count(5)->create();
    }
}