<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    /**
     * المدير يمر لكل الصلاحيات
     */
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

    public function view(User $user, Contract $contract): bool
    {
        // المحامي: عقوده فقط
        if ($user->isLawyer()) {
            return $contract->users_id === $user->id
                || $contract->client?->users_id === $user->id;
        }

        // الموكل: عقوده فقط
        if ($user->isClient()) {
            return $contract->client?->users_id === $user->id;
        }

        // الموظف: عرض فقط
        if ($user->hasRole('موظف إداري')) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['مدير النظام', 'محامي']);
    }

    public function update(User $user, Contract $contract): bool
    {
        // المحامي: عقوده فقط
        if ($user->isLawyer()) {
            return $contract->users_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Contract $contract): bool
    {
        if ($user->isLawyer()) {
            return $contract->users_id === $user->id;
        }

        return false;
    }
}