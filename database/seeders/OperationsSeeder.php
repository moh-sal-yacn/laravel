<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\CaseSession;
use App\Models\LegalPrecedent;
use Illuminate\Database\Seeder;

class OperationsSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ الجداول التي سيتم توليد بيانات وهمية لها فقط
        CaseSession::factory()->count(30)->create();
        Appointment::factory()->count(15)->create();
        LegalPrecedent::factory()->count(10)->create();
    }
}