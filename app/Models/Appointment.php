<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = ['appointment_date', 'status', 'bookings_id', 'users_id', 'clients_id'];

    protected function casts(): array
    {
        return ['appointment_date' => 'datetime'];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'bookings_id');
    }

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clients_id');
    }
}
