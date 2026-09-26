<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class, // 1. الأدوار والصلاحيات
            UserSeeder::class,           // 2. المستخدمون (يحتاجون الأدوار)
            ClientSeeder::class,         // 3. الموكلون (يحتاجون المستخدمين)
            CourtCategorySeeder::class,  // 4. المحاكم والتصنيفات
            CaseSeeder::class,           // 5. القضايا والمشاركون (يحتاج الموكلين والمحاكم والتصنيفات)
            OperationsSeeder::class,     // 6. الجلسات + المواعيد + السوابق فقط
        ]);
    }
}