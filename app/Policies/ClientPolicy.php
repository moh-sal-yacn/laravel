<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Client $client): bool
    {
        // المحامي: عملاؤه فقط
        if ($user->isLawyer()) {
            return $client->cases()
                ->whereHas('participants', function ($q) use ($user) {
                    $q->where('users_id', $user->id);
                })
                ->exists()
                || $client->contracts()->where('users_id', $user->id)->exists();
        }

        // الموكل: بياناته فقط
        if ($user->isClient()) {
            return $client->users_id === $user->id;
        }

        // الموظف: عرض فقط
        if ($user->hasRole('موظف إداري')) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['مدير النظام', 'محامي', 'موظف إداري']);
    }

    public function update(User $user, Client $client): bool
    {
        // الموكل: بياناته فقط (محدود)
        if ($user->isClient()) {
            return $client->users_id === $user->id;
        }

        // المدير (before)
        // الموظف الإداري
        if ($user->hasRole('موظف إداري')) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Client $client): bool
    {
        return false; // فقط المدير (before)
    }
}