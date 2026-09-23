@extends('cms.parent')
@section('title', 'المستخدمون')
@section('page-title', 'إدارة المستخدمين')

@php
    $typeLabels = ['admin' => 'مدير', 'lawyer' => 'محامي', 'client' => 'موكل', 'staff' => 'موظف'];
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-bold">قائمة المستخدمين</span>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> مستخدم جديد</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>الاسم</th><th>البريد الإلكتروني</th><th>النوع</th><th>الأدوار</th><th>الحالة</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $typeLabels[$user->user_type] ?? $user->user_type }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="badge text-bg-secondary">{{ $role->role_name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge text-bg-success">مفعّل</span>
                                @else
                                    <span class="badge text-bg-danger">موقوف</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف هذا المستخدم؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">لا يوجد مستخدمون.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($users->hasPages())<div class="card-footer bg-white">{{ $users->links() }}</div>@endif
</div>
@endsection
