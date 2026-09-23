<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('users', 'permissions')->get();

        return view('cms.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::all();

        return view('cms.roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role_name' => 'required|string|max:45|unique:roles,role_name',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(collect($data)->except('permissions')->all());
        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'تم إنشاء الدور بنجاح.');
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::all();
        $role->load('permissions');

        return view('cms.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'role_name' => 'required|string|max:45|unique:roles,role_name,'.$role->id,
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(collect($data)->except('permissions')->all());
        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'تم تحديث الدور.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'تم حذف الدور.');
    }
}
