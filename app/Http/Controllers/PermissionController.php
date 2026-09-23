<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::withCount('roles')->get()->groupBy('module');

        return view('cms.permissions.index', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'permission_name' => 'required|string|max:45',
            'module' => 'required|string|max:45',
        ]);

        Permission::create($data);

        return redirect()->route('permissions.index')->with('success', 'تمت إضافة الصلاحية بنجاح.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'تم حذف الصلاحية.');
    }
}
