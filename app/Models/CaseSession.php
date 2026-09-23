<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Physical table is "case_sessions" (renamed from the ERD's "sessions" to avoid
// colliding with Laravel's own database session driver table).
class CaseSession extends Model
{
    use HasFactory;

    protected $table = 'case_sessions';

    protected $fillable = ['session_date', 'session_notes', 'next_session_date', 'cases_id', 'users_id'];

    protected function casts(): array
    {
        return [
            'session_date' => 'datetime',
            'next_session_date' => 'date',
        ];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(CourtCase::class, 'cases_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
