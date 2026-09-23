<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['role_name', 'description'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'roles_has_permissions', 'roles_id', 'permissions_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_has_roles', 'roles_id', 'users_id');
    }
}
