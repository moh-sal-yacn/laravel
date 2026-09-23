@extends('cms.parent')
@section('title', 'مستخدم جديد')
@section('page-title', 'إضافة مستخدم جديد')

@section('content')
<div class="card col-lg-7">
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" minlength="8" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" maxlength="45">
                </div>
                <div class="col-md-6">
                    <label class="form-label">نوع المستخدم</label>
                    <select name="user_type" class="form-select" required>
                        <option value="admin" @selected(old('user_type') === 'admin')>مدير</option>
                        <option value="lawyer" @selected(old('user_type') === 'lawyer')>محامي</option>
                        <option value="client" @selected(old('user_type') === 'client')>موكل</option>
                        <option value="staff" @selected(old('user_type') === 'staff')>موظف</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الأدوار</label>
                    <div class="border rounded-3 p-2">
                        @foreach ($roles as $role)
                            <div class="form-check">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-check-input" id="role{{ $role->id }}">
                                <label class="form-check-label" for="role{{ $role->id }}">{{ $role->role_name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">حفظ</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
