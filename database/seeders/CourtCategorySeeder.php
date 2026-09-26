<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Court;
use Illuminate\Database\Seeder;

class CourtCategorySeeder extends Seeder
{
    public function run(): void
    {
        // محاكم فلسطينية واقعية (تركّز على غزة)
        $courts = [
            // محاكم الصلح
            ['court_name' => 'محكمة صلح غزة',           'jurisdiction' => 'مدني',           'location' => 'غزة'],
            ['court_name' => 'محكمة صلح خان يونس',      'jurisdiction' => 'مدني',           'location' => 'خان يونس'],
            ['court_name' => 'محكمة صلح دير البلح',     'jurisdiction' => 'مدني',           'location' => 'دير البلح'],
            ['court_name' => 'محكمة صلح رفح',           'jurisdiction' => 'مدني',           'location' => 'رفح'],
            ['court_name' => 'محكمة صلح جباليا',        'jurisdiction' => 'مدني',           'location' => 'جباليا'],

            // محاكم البداية
            ['court_name' => 'محكمة بداية غزة',         'jurisdiction' => 'جزائي',          'location' => 'غزة'],
            ['court_name' => 'محكمة بداية خان يونس',    'jurisdiction' => 'جزائي',          'location' => 'خان يونس'],
            ['court_name' => 'محكمة بداية رفح',         'jurisdiction' => 'جزائي',          'location' => 'رفح'],
            ['court_name' => 'محكمة بداية دير البلح',   'jurisdiction' => 'جزائي',          'location' => 'دير البلح'],
            ['court_name' => 'محكمة بداية شمال غزة',    'jurisdiction' => 'جزائي',          'location' => 'شمال غزة'],

            // المحاكم المتخصصة
            ['court_name' => 'المحكمة التجارية بغزة',           'jurisdiction' => 'تجاري',       'location' => 'غزة'],
            ['court_name' => 'محكمة الأحوال الشخصية بغزة',      'jurisdiction' => 'أحوال شخصية', 'location' => 'غزة'],
            ['court_name' => 'محكمة العمل بغزة',                'jurisdiction' => 'عمالي',       'location' => 'غزة'],
            ['court_name' => 'محكمة الأحداث بغزة',              'jurisdiction' => 'جزائي',       'location' => 'غزة'],
            ['court_name' => 'محكمة الاستئناف بغزة',            'jurisdiction' => 'مدني',        'location' => 'غزة'],
            ['court_name' => 'المحكمة العليا الفلسطينية',       'jurisdiction' => 'مدني',        'location' => 'غزة'],
            ['court_name' => 'محكمة القضاء الإداري',            'jurisdiction' => 'إداري',       'location' => 'غزة'],
        ];

        foreach ($courts as $court) {
            Court::firstOrCreate(
                ['court_name' => $court['court_name']],
                $court
            );
        }

        // تصنيفات القضايا (عربية كاملة)
        $categories = [
            'قضايا مدنية',
            'قضايا جزائية',
            'قضايا تجارية',
            'قضايا عمالية',
            'قضايا أحوال شخصية',
            'قضايا عقارية',
            'قضايا إدارية',
            'قضايا دستورية',
            'قضايا أسرية',
            'قضايا إرث وتركات',
            'قضايا تنفيذ أحكام',
            'قضايا تحكيم',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['category_name' => $name]);
        }
    }
}