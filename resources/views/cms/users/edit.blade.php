@extends('cms.parent')
@section('title', 'تعديل مستخدم')
@section('page-title', 'تعديل بيانات المستخدم')

@section('content')
<div class="card col-lg-7">
    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" maxlength="45">
                </div>
                <div class="col-md-6">
                    <label class="form-label">نوع المستخدم</label>
                    <select name="user_type" class="form-select" required>
                        @foreach (['admin' => 'مدير', 'lawyer' => 'محامي', 'client' => 'موكل', 'staff' => 'موظف'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('user_type', $user->user_type) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحالة</label>
                    <select name="is_active" class="form-select">
                        <option value="1" @selected(old('is_active', $user->is_active) == 1)>مفعّل</option>
                        <option value="0" @selected(old('is_active', $user->is_active) == 0)>موقوف</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الأدوار</label>
                    <div class="border rounded-3 p-2">
                        @foreach ($roles as $role)
                            <div class="form-check">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-check-input" id="role{{ $role->id }}"
                                    @checked($user->roles->contains($role->id))>
                                <label class="form-check-label" for="role{{ $role->id }}">{{ $role->role_name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">حفظ التعديلات</button>
                <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
