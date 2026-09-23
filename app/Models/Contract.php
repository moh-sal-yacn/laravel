<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'contract_type', 'contract_status', 'parties', 'contract_value',
        'signed_at', 'clients_id', 'users_id',
    ];

    protected function casts(): array
    {
        return [
            'contract_value' => 'decimal:2',
            'signed_at' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clients_id');
    }

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'related', 'related_type', 'related_id')
            ->where('related_type', 'contract');
    }

    // Contracts don't have a direct FK from financial_records in the ERD;
    // payments against a contract are correlated through the shared client.
    public function payments()
    {
        return FinancialRecord::where('clients_id', $this->clients_id);
    }

    public function totalPaid(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function remainingBalance(): float
    {
        return (float) $this->contract_value - $this->totalPaid();
    }
}
