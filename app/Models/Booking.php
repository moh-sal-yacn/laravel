<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = ['visitor_name', 'booking_type', 'preferred_date', 'status', 'users_id', 'clients_id'];

    protected function casts(): array
    {
        return ['preferred_date' => 'datetime'];
    }

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clients_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'bookings_id');
    }

    public function serviceRatings(): HasMany
    {
        return $this->hasMany(ServiceRating::class, 'bookings_id');
    }
}
