<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'clients';

    protected $fillable = ['client_kind', 'national_id', 'users_id'];

    // ═══════════════ Activity Log ═══════════════
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['client_kind', 'national_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('clients')
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'أنشأ الموكل',
                'updated' => 'عدّل الموكل',
                'deleted' => 'حذف الموكل',
                default   => $eventName,
            });
    }

    // ═══════════════ العلاقات ═══════════════
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function cases(): HasMany
    {
        return $this->hasMany(CourtCase::class, 'clients_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'clients_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'clients_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'clients_id');
    }

    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class, 'clients_id');
    }

    public function serviceRatings(): HasMany
    {
        return $this->hasMany(ServiceRating::class, 'clients_id');
    }
}