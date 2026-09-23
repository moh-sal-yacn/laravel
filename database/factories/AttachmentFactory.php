<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\CourtCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        return [
            'related_id' => CourtCase::inRandomOrder()->value('id'),
            'related_type' => 'case',
            'file_url' => 'attachments/'.fake()->uuid().'.pdf',
            'file_type' => 'pdf',
            'users_id' => User::inRandomOrder()->value('id'),
        ];
    }
}
