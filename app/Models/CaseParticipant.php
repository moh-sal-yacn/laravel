<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CaseParticipant extends Pivot
{
    protected $table = 'cases_has_users';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['cases_id', 'users_id', 'role_in_case', 'assigned_at', 'is_active'];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
