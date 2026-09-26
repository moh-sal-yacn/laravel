<?php

namespace App\Policies;

use App\Models\CourtCase;
use App\Models\User;

class CasePolicy
{
    /**
     * المدير يمر مباشرة لكل الصلاحيات
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    /**
     * عرض قائمة القضايا
     * كل المسجَّلين يمكنهم رؤية القائمة (لكن سيتم تصفيتها لاحقاً)
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * عرض قضية محددة
     */
    public function view(User $user, CourtCase $case): bool
    {
        // ─── محامي ───
        // يستطيع الرؤية إذا:
        // 1. هو طرف في القضية (participant)
        // 2. أو هو محامي الموكل المرتبط بالقضية
        if ($user->isLawyer()) {
            return $case->participants()->where('users_id', $user->id)->exists()
                || $case->client?->users_id === $user->id;
        }

        // ─── موكل ───
        // يرى فقط قضاياه
        if ($user->isClient()) {
            return $case->client?->users_id === $user->id;
        }

        // ─── موظف إداري ───
        // عرض فقط
        if ($user->hasRole('موظف إداري')) {
            return true;
        }

        return false;
    }

    /**
     * إنشاء قضية جديدة
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['مدير النظام', 'محامي']);
    }

    /**
     * تعديل قضية
     */
    public function update(User $user, CourtCase $case): bool
    {
        // المحامي: فقط إذا هو طرف في القضية
        if ($user->isLawyer()) {
            return $case->participants()->where('users_id', $user->id)->exists();
        }

        // الموظف الإداري: لا يعدّل قضايا
        return false;
    }

    /**
     * حذف قضية
     */
    public function delete(User $user, CourtCase $case): bool
    {
        // المحامي: فقط إذا هو طرف في القضية
        if ($user->isLawyer()) {
            return $case->participants()->where('users_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * استعادة قضية محذوفة (soft delete)
     */
    public function restore(User $user, CourtCase $case): bool
    {
        return false; // فقط المدير (عبر before)
    }

    /**
     * حذف نهائي
     */
    public function forceDelete(User $user, CourtCase $case): bool
    {
        return false; // فقط المدير (عبر before)
    }
}