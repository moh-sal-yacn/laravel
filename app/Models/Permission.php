<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['permission_name', 'module'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'roles_has_permissions', 'permissions_id', 'roles_id');
    }
}
