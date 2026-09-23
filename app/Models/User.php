<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    public $timestamps = false; // only created_at exists per ERD

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'user_type', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_has_roles', 'users_id', 'roles_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'users_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'users_id');
    }

    public function cases(): BelongsToMany
    {
        return $this->belongsToMany(CourtCase::class, 'cases_has_users', 'users_id', 'cases_id')
            ->using(CaseParticipant::class)
            ->withPivot(['role_in_case', 'assigned_at', 'is_active']);
    }

    public function caseSessions(): HasMany
    {
        return $this->hasMany(CaseSession::class, 'users_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'users_id');
    }

    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class, 'users_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'users_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'users_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'users_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'users_id');
    }

    public function contactChannels(): HasMany
    {
        return $this->hasMany(ContactChannel::class, 'users_id');
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('role_name', $roleName)->exists();
    }
}
