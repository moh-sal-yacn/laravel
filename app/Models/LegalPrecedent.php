<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalPrecedent extends Model
{
    use HasFactory;

    protected $table = 'legal_precedents';

    protected $fillable = ['source', 'title', 'summary', 'external_link', 'ruling_date', 'cases_id'];

    protected function casts(): array
    {
        return ['ruling_date' => 'date'];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(CourtCase::class, 'cases_id');
    }
}
