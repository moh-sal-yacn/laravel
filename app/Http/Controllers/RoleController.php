<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * عرض قائمة الأدوار مع عدد المستخدمين والصلاحيات
     */
    public function index(): View
    {
        $roles = Role::withCount('users', 'permissions')
            ->latest()
            ->get();

        return view('cms.roles.index', compact('roles'));
    }

    /**
     * عرض نموذج إنشاء دور جديد
     */
    public function create(): View
    {
        $permissions = Permission::orderBy('module')->get();

        return view('cms.roles.create', compact('permissions'));
    }

    /**
     * حفظ دور جديد
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $role = Role::create([
            'role_name'   => $data['role_name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح.');
    }

    /**
     * عرض نموذج تعديل دور
     */
    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('module')->get();
        $role->load('permissions');

        return view('cms.roles.edit', compact('role', 'permissions'));
    }

    /**
     * تحديث بيانات دور
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $data = $request->validated();

        $role->update([
            'role_name'   => $data['role_name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم تحديث الدور بنجاح.');
    }

    /**
     * حذف دور
     */
    public function destroy(Role $role): RedirectResponse
    {
        // منع حذف الأدوار الأساسية
        $protectedRoles = ['مدير النظام', 'محامي', 'موكل', 'موظف إداري'];
        if (in_array($role->role_name, $protectedRoles)) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'لا يمكن حذف الأدوار الأساسية في النظام.');
        }

        // منع حذف دور مرتبط بمستخدمين
        if ($role->users()->count() > 0) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'لا يمكن حذف دور مرتبط بمستخدمين. قم بنقلهم لدور آخر أولاً.');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'تم حذف الدور بنجاح.');
    }
}