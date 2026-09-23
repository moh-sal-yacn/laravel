<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRating extends Model
{
    use HasFactory;

    protected $table = 'service_ratings';

    public $timestamps = false;

    protected $fillable = ['rating', 'comment', 'clients_id', 'categories_id', 'cases_id', 'bookings_id'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clients_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(CourtCase::class, 'cases_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'bookings_id');
    }
}
