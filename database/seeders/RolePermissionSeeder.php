<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect([
            ['role_name' => 'Admin', 'description' => 'مدير النظام - صلاحيات كاملة'],
            ['role_name' => 'Lawyer', 'description' => 'محامي - إدارة القضايا والعقود'],
            ['role_name' => 'Client', 'description' => 'موكل - عرض قضاياه وعقوده فقط'],
            ['role_name' => 'Staff', 'description' => 'موظف إداري'],
        ])->map(fn ($r) => Role::create($r));

        $permissions = collect([
            ['permission_name' => 'إدارة القضايا', 'module' => 'cases'],
            ['permission_name' => 'إدارة العقود', 'module' => 'contracts'],
            ['permission_name' => 'إدارة المستخدمين', 'module' => 'users'],
            ['permission_name' => 'إدارة المدفوعات', 'module' => 'finance'],
            ['permission_name' => 'إدارة الجلسات', 'module' => 'sessions'],
            ['permission_name' => 'عرض التقارير', 'module' => 'reports'],
        ])->map(fn ($p) => Permission::create($p));

        $admin = $roles->firstWhere('role_name', 'Admin');
        $lawyer = $roles->firstWhere('role_name', 'Lawyer');
        $client = $roles->firstWhere('role_name', 'Client');

        // Admin: كل الصلاحيات
        $admin->permissions()->attach($permissions->pluck('id'));

        // Lawyer: كل شيء ما عدا إدارة المستخدمين
        $lawyer->permissions()->attach(
            $permissions->reject(fn ($p) => $p->permission_name === 'إدارة المستخدمين')->pluck('id')
        );

        // Client: عرض التقارير فقط
        $client->permissions()->attach(
            $permissions->where('permission_name', 'عرض التقارير')->pluck('id')
        );
    }
}
