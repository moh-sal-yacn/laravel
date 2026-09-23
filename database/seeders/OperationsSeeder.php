<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Article;
use App\Models\Attachment;
use App\Models\Booking;
use App\Models\CaseSession;
use App\Models\ContactChannel;
use App\Models\Contract;
use App\Models\FeaturedCompany;
use App\Models\FinancialRecord;
use App\Models\LegalPrecedent;
use App\Models\ServiceRating;
use Illuminate\Database\Seeder;

// Seeds everything that depends on cases/clients/users already existing.
class OperationsSeeder extends Seeder
{
    public function run(): void
    {
        CaseSession::factory()->count(30)->create();
        Contract::factory()->count(15)->create();
        FinancialRecord::factory()->count(40)->create();
        Booking::factory()->count(15)->create();
        Appointment::factory()->count(15)->create();
        Attachment::factory()->count(25)->create();
        ServiceRating::factory()->count(20)->create();
        LegalPrecedent::factory()->count(10)->create();
        ContactChannel::factory()->count(10)->create();
        Article::factory()->count(8)->create();
        FeaturedCompany::factory()->count(6)->create();
    }
}
