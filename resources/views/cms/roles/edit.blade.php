@extends('cms.parent')
@section('title', 'تعديل دور')
@section('page-title', 'تعديل الدور: '.$role->role_name)

@section('content')
<div class="card col-lg-6">
    <div class="card-body">
        <form action="{{ route('roles.update', $role) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">اسم الدور</label>
                <input type="text" name="role_name" class="form-control" value="{{ old('role_name', $role->role_name) }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $role->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">الصلاحيات</label>
                <div class="border rounded-3 p-2">
                    @foreach ($permissions as $permission)
                        <div class="form-check">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" id="perm{{ $permission->id }}"
                                @checked($role->permissions->contains($permission->id))>
                            <label class="form-check-label" for="perm{{ $permission->id }}">{{ $permission->permission_name }} <span class="text-muted small">({{ $permission->module }})</span></label>
                        </div>
                    @endforeach
                </div>
            </div>
            <button class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
