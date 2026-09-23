<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    use HasFactory;

    protected $table = 'attachments';

    public $timestamps = false;

    protected $fillable = ['related_id', 'related_type', 'file_url', 'file_type', 'uploaded_at', 'users_id'];

    protected function casts(): array
    {
        return ['uploaded_at' => 'datetime'];
    }

    // Custom polymorphic mapping: related_type stores 'case' | 'contract' | 'user'
    // instead of Laravel's default FQCN morph strings.
    public function related(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'related_type', 'related_id')
            ->morphMap([
                'case' => CourtCase::class,
                'contract' => Contract::class,
                'user' => User::class,
            ]);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
