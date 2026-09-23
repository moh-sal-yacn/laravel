<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Court;
use Illuminate\Database\Seeder;

class CourtCategorySeeder extends Seeder
{
    public function run(): void
    {
        Court::factory()->count(5)->create();

        foreach (['مدني', 'جنائي', 'تجاري', 'عمالي', 'أحوال شخصية', 'عقاري'] as $name) {
            Category::firstOrCreate(['category_name' => $name]);
        }
    }
}
