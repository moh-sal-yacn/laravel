<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialRecord extends Model
{
    use HasFactory;

    protected $table = 'financial_records';

    protected $fillable = [
        'amount', 'transaction_type', 'transaction_date', 'notes',
        'cases_id', 'clients_id', 'users_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(CourtCase::class, 'cases_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clients_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
