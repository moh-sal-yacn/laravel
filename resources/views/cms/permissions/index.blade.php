@extends('cms.parent')
@section('title', 'الصلاحيات')
@section('page-title', 'إدارة الصلاحيات')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        @forelse ($permissions as $module => $items)
            <div class="card mb-3">
                <div class="card-header bg-white fw-bold">{{ $module }}</div>
                <ul class="list-group list-group-flush">
                    @foreach ($items as $permission)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $permission->permission_name }} <span class="badge text-bg-light ms-1">{{ $permission->roles_count }} دور</span></span>
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('حذف هذه الصلاحية؟');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="text-muted">لا توجد صلاحيات مسجلة.</div>
        @endforelse
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white fw-bold">إضافة صلاحية جديدة</div>
            <div class="card-body">
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">اسم الصلاحية</label>
                        <input type="text" name="permission_name" class="form-control" maxlength="45" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوحدة (Module)</label>
                        <input type="text" name="module" class="form-control" maxlength="45" required>
                    </div>
                    <button class="btn btn-primary w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
