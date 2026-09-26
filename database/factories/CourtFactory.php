<?php

namespace Database\Factories;

use App\Models\Court;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourtFactory extends Factory
{
    protected $model = Court::class;

    public function definition(): array
    {
        // أسماء محاكم فلسطينية واقعية (تركّز على قطاع غزة)
        $courtNames = [
            // محاكم البداية والصلح
            'محكمة صلح غزة',
            'محكمة صلح خان يونس',
            'محكمة صلح دير البلح',
            'محكمة صلح رفح',
            'محكمة صلح جباليا',
            'محكمة بداية غزة',
            'محكمة بداية خان يونس',
            'محكمة بداية رفح',
            'محكمة بداية دير البلح',
            'محكمة بداية شمال غزة',
            // المحاكم المتخصصة
            'المحكمة التجارية بغزة',
            'محكمة الأحوال الشخصية بغزة',
            'محكمة الأحداث بغزة',
            'محكمة العمل بغزة',
            'محكمة الاستئناف بغزة',
            'المحكمة العليا الفلسطينية',
            'المحكمة الدستورية العليا',
            'محكمة القضاء الإداري',
        ];

        $jurisdictions = ['مدني', 'جزائي', 'تجاري', 'أحوال شخصية', 'عمالي', 'إداري'];

        // المواقع (مناطق قطاع غزة والضفة)
        $locations = [
            'غزة',
            'شمال غزة',
            'جباليا',
            'بيت لاهيا',
            'بيت حانون',
            'دير البلح',
            'خان يونس',
            'رفح',
            'النصيرات',
            'المغازي',
            'البريج',
            'رفح',
        ];

        return [
            'court_name'   => fake()->randomElement($courtNames),
            'jurisdiction' => fake()->randomElement($jurisdictions),
            'location'     => fake()->randomElement($locations),
        ];
    }
}