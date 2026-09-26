<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Contract extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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

    // ═══════════════ Activity Log ═══════════════
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'contract_type', 'contract_status', 'parties',
                'contract_value', 'signed_at', 'clients_id', 'users_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('contracts')
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'أنشأ العقد',
                'updated' => 'عدّل العقد',
                'deleted' => 'حذف العقد',
                default   => $eventName,
            });
    }

    // ═══════════════ العلاقات ═══════════════
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