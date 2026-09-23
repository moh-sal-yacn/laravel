<?php

namespace Database\Seeders;

use App\Models\CaseParticipant;
use App\Models\Client;
use App\Models\CourtCase;
use App\Models\User;
use Illuminate\Database\Seeder;

class CaseSeeder extends Seeder
{
    public function run(): void
    {
        CourtCase::factory()->count(20)->create();

        $lawyers = User::where('user_type', 'lawyer')->pluck('id');

        CourtCase::all()->each(function (CourtCase $case) use ($lawyers) {
            // Link the case's own client as a participant.
            $clientUserId = Client::find($case->clients_id)?->users_id;

            $rows = [];
            if ($clientUserId) {
                $rows[$clientUserId] = [
                    'role_in_case' => 'موكل',
                    'assigned_at' => now(),
                    'is_active' => true,
                ];
            }
            if ($lawyers->isNotEmpty()) {
                $rows[$lawyers->random()] = [
                    'role_in_case' => 'محامي أساسي',
                    'assigned_at' => now(),
                    'is_active' => true,
                ];
            }
            if ($rows) {
                $case->participants()->syncWithoutDetaching($rows);
            }
        });
    }
}
