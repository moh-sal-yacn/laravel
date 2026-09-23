<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['category_name', 'categories_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'categories_id');
    }

    public function cases(): HasMany
    {
        return $this->hasMany(CourtCase::class, 'categories_id');
    }

    public function serviceRatings(): HasMany
    {
        return $this->hasMany(ServiceRating::class, 'categories_id');
    }
}
