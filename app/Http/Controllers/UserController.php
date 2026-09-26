<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين مع الفلاتر المتقدمة
     */
    public function index(Request $request): View
    {
        $users = User::with('roles')
            // ─── الفلاتر ───
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(fn ($sub) => $sub->where('name', 'like', "%{$search}%")
                                            ->orWhere('email', 'like', "%{$search}%")
                                            ->orWhere('phone', 'like', "%{$search}%"));
            })
            ->when($request->filled('type'), fn ($q) => $q->where('user_type', $request->type))
            ->when($request->filled('role'), fn ($q) => $q->whereHas('roles', fn ($r) => $r->where('roles.id', $request->role)))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status == 'active'))
            ->when($request->filled('registered_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->registered_from))
            ->when($request->filled('registered_to'), fn ($q) => $q->whereDate('created_at', '<=', $request->registered_to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $roles = Role::orderBy('role_name')->get();

        return view('cms.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('cms.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'phone'     => $data['phone'] ?? null,
            'user_type' => $data['user_type'],
            'is_active' => $data['is_active'] ?? true,
        ]);

        $user->roles()->sync($data['roles'] ?? []);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    public function show(User $user): View
    {
        $user->load('roles', 'profile');
        return view('cms.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        $user->load('roles');
        return view('cms.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'user_type' => $data['user_type'],
            'is_active' => $data['is_active'] ?? $user->is_active,
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $user->roles()->sync($data['roles'] ?? []);

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->email === 'admin@lawfirm.test') {
            return redirect()
                ->route('users.index')
                ->with('error', 'لا يمكن حذف حساب المدير الرئيسي.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }
}