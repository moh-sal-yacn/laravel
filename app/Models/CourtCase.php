<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CourtCase extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'cases';

    protected $fillable = [
        'case_number', 'case_title', 'case_status', 'archived_at',
        'opened_at', 'description', 'clients_id', 'categories_id', 'courts_id',
    ];

    protected function casts(): array
    {
        return [
            'archived_at' => 'datetime',
            'opened_at' => 'date',
        ];
    }

    // ═══════════════ Activity Log ═══════════════
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'case_number', 'case_title', 'case_status',
                'description', 'clients_id', 'courts_id', 'categories_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('cases')
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'أنشأ القضية',
                'updated' => 'عدّل القضية',
                'deleted' => 'حذف القضية',
                default   => $eventName,
            });
    }

    // ═══════════════ العلاقات ═══════════════
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clients_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'courts_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cases_has_users', 'cases_id', 'users_id')
            ->using(CaseParticipant::class)
            ->withPivot(['role_in_case', 'assigned_at', 'is_active']);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CaseSession::class, 'cases_id');
    }

    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class, 'cases_id');
    }

    public function legalPrecedents(): HasMany
    {
        return $this->hasMany(LegalPrecedent::class, 'cases_id');
    }

    public function serviceRatings(): HasMany
    {
        return $this->hasMany(ServiceRating::class, 'cases_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'related', 'related_type', 'related_id')
            ->where('related_type', 'case');
    }

    public function totalPaid(): float
    {
        return (float) $this->financialRecords()->sum('amount');
    }
}