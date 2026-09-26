<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\StorePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermissionController extends Controller
{
    /**
     * عرض قائمة الصلاحيات مجمّعة حسب الوحدة
     */
    public function index(): View
    {
        $permissions = Permission::withCount('roles')
            ->orderBy('module')
            ->get()
            ->groupBy('module');

        return view('cms.permissions.index', compact('permissions'));
    }

    /**
     * حفظ صلاحية جديدة
     */
    public function store(StorePermissionRequest $request): RedirectResponse
    {
        Permission::create($request->validated());

        return redirect()
            ->route('permissions.index')
            ->with('success', 'تمت إضافة الصلاحية بنجاح.');
    }

    /**
     * حذف صلاحية
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        // منع حذف صلاحية مرتبطة بأدوار
        $rolesCount = $permission->roles()->count();
        if ($rolesCount > 0) {
            return redirect()
                ->route('permissions.index')
                ->with('error', "لا يمكن حذف الصلاحية لأنها مرتبطة بـ {$rolesCount} دور. قم بفك الارتباط أولاً.");
        }

        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح.');
    }
}