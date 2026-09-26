<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ────── الأدوار ──────
        $rolesData = [
            [
                'role_name'   => 'مدير النظام',
                'description' => 'مدير النظام - صلاحيات كاملة على جميع وحدات المكتب',
            ],
            [
                'role_name'   => 'محامي',
                'description' => 'محامي - إدارة القضايا والعقود والجلسات والموكلين',
            ],
            [
                'role_name'   => 'موكل',
                'description' => 'موكل - عرض قضاياه وعقوده ومواعيده فقط',
            ],
            [
                'role_name'   => 'موظف إداري',
                'description' => 'موظف إداري - إدارة المواعيد والمراسلات والسجلات',
            ],
        ];

        $roles = collect($rolesData)->map(fn ($r) => Role::create($r));

        // ────── الصلاحيات ──────
        $permissionsData = [
            ['permission_name' => 'إدارة القضايا',          'module' => 'cases'],
            ['permission_name' => 'إدارة العقود',           'module' => 'contracts'],
            ['permission_name' => 'إدارة المستخدمين',       'module' => 'users'],
            ['permission_name' => 'إدارة المدفوعات',        'module' => 'finance'],
            ['permission_name' => 'إدارة الجلسات',          'module' => 'sessions'],
            ['permission_name' => 'إدارة المواعيد',         'module' => 'appointments'],
            ['permission_name' => 'إدارة الموكلين',         'module' => 'clients'],
            ['permission_name' => 'إدارة المحاكم',          'module' => 'courts'],
            ['permission_name' => 'إدارة السوابق القضائية', 'module' => 'precedents'],
            ['permission_name' => 'إدارة المقالات',         'module' => 'articles'],
            ['permission_name' => 'عرض التقارير',           'module' => 'reports'],
            ['permission_name' => 'إدارة الإعدادات',        'module' => 'settings'],
        ];

        $permissions = collect($permissionsData)->map(fn ($p) => Permission::create($p));

        // ────── ربط الأدوار بالصلاحيات ──────
        $admin   = $roles->firstWhere('role_name', 'مدير النظام');
        $lawyer  = $roles->firstWhere('role_name', 'محامي');
        $client  = $roles->firstWhere('role_name', 'موكل');
        $staff   = $roles->firstWhere('role_name', 'موظف إداري');

        // مدير النظام: كل الصلاحيات
        $admin->permissions()->attach($permissions->pluck('id'));

        // المحامي: كل شيء ما عدا إدارة المستخدمين والإعدادات
        $lawyer->permissions()->attach(
            $permissions
                ->reject(fn ($p) => in_array($p->permission_name, ['إدارة المستخدمين', 'إدارة الإعدادات']))
                ->pluck('id')
        );

        // الموظف الإداري: المواعيد + الموكلين + المراسلات + عرض التقارير
        $staff->permissions()->attach(
            $permissions
                ->whereIn('permission_name', [
                    'إدارة المواعيد',
                    'إدارة الموكلين',
                    'عرض التقارير',
                ])
                ->pluck('id')
        );

        // الموكل: عرض التقارير فقط
        $client->permissions()->attach(
            $permissions->where('permission_name', 'عرض التقارير')->pluck('id')
        );
    }
}