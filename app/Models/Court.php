<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    use HasFactory;

    protected $table = 'courts';

    protected $fillable = ['court_name', 'jurisdiction', 'location'];

    public function cases(): HasMany
    {
        return $this->hasMany(CourtCase::class, 'courts_id');
    }
}
