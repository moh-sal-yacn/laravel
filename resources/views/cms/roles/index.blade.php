@extends('cms.parent')
@section('title', 'الأدوار')
@section('page-title', 'إدارة الأدوار والصلاحيات')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-bold">قائمة الأدوار</span>
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> دور جديد</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>اسم الدور</th><th>الوصف</th><th>عدد المستخدمين</th><th>عدد الصلاحيات</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td class="fw-semibold">{{ $role->role_name }}</td>
                            <td class="text-muted small">{{ $role->description }}</td>
                            <td><span class="badge text-bg-light">{{ $role->users_count }}</span></td>
                            <td><span class="badge text-bg-light">{{ $role->permissions_count }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف هذا الدور؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">لا توجد أدوار مسجلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
