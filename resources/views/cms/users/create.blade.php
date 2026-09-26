@extends('cms.parent')

@section('title', 'مستخدم جديد')
@section('page-title', 'إضافة مستخدم جديد')

@section('content')
<div class="row">

    {{-- النموذج --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-person-plus-fill text-warning me-2"></i>
                    بيانات المستخدم الجديد
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">

                        {{-- الاسم --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-muted me-1"></i>
                                الاسم الكامل <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   maxlength="45"
                                   placeholder="الاسم الثلاثي"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- البريد --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill text-muted me-1"></i>
                                البريد الإلكتروني <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   maxlength="45"
                                   placeholder="user@example.com"
                                   dir="ltr"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- كلمة المرور --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock-fill text-muted me-1"></i>
                                كلمة المرور <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   name="password"
                                   minlength="8"
                                   placeholder="8 أحرف على الأقل"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- تأكيد كلمة المرور --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock-fill text-muted me-1"></i>
                                تأكيد كلمة المرور <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   placeholder="أعد كتابة كلمة المرور"
                                   class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الهاتف --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone-fill text-muted me-1"></i>
                                رقم الهاتف <small class="text-muted">(اختياري)</small>
                            </label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   maxlength="45"
                                   placeholder="05xxxxxxxx"
                                   class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- نوع المستخدم --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge-fill text-muted me-1"></i>
                                نوع المستخدم <span class="text-danger">*</span>
                            </label>
                            <select name="user_type" class="form-select @error('user_type') is-invalid @enderror">
                                <option value="admin"  @selected(old('user_type') === 'admin')>مدير النظام</option>
                                <option value="lawyer" @selected(old('user_type') === 'lawyer')>محامي</option>
                                <option value="client" @selected(old('user_type') === 'client')>موكل</option>
                                <option value="staff"  @selected(old('user_type') === 'staff')>موظف إداري</option>
                            </select>
                            @error('user_type')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الحالة --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-toggle-on text-muted me-1"></i>
                                حالة الحساب
                            </label>
                            <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                <option value="1" @selected(old('is_active', 1) == 1)>مفعّل</option>
                                <option value="0" @selected(old('is_active') == 0)>موقوف</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الأدوار --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-shield-lock-fill text-muted me-1"></i>
                                الأدوار <small class="text-muted">(يمكن اختيار أكثر من دور)</small>
                            </label>
                            <div class="border rounded-3 p-3 @error('roles') border-danger @enderror">
                                <div class="row">
                                    @foreach ($roles as $role)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       name="roles[]"
                                                       value="{{ $role->id }}"
                                                       class="form-check-input"
                                                       id="role{{ $role->id }}"
                                                       @checked(in_array($role->id, old('roles', [])))>
                                                <label class="form-check-label" for="role{{ $role->id }}">
                                                    {{ $role->role_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('roles')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    {{-- أزرار --}}
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> حفظ المستخدم
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> إلغاء
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- البطاقة الجانبية --}}
    <div class="col-lg-4">
        <div class="card border-0" style="background: linear-gradient(135deg, #fffbf0 0%, #fff5d6 100%);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                    إرشادات سريعة
                </h6>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>كلمة المرور</strong>: 8 أحرف على الأقل.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>البريد</strong>: فريد لكل مستخدم.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>الأدوار</strong>: مرتبطة بنوع المستخدم تلقائياً.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>الموكل</strong>: سيُنشأ له سجل موكل تلقائياً.
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection