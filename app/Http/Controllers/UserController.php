<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->paginate(20);

        return view('cms.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();

        return view('cms.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:45',
            'email' => 'required|email|max:45|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:45',
            'user_type' => 'required|in:admin,lawyer,client,staff',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            ...collect($data)->except(['password', 'roles'])->all(),
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->sync($data['roles'] ?? []);

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح.');
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

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:45',
            'email' => 'required|email|max:45|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:45',
            'user_type' => 'required|in:admin,lawyer,client,staff',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update(collect($data)->except('roles')->all());
        $user->roles()->sync($data['roles'] ?? []);

        return redirect()->route('users.show', $user)->with('success', 'تم تحديث بيانات المستخدم.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم.');
    }
}
