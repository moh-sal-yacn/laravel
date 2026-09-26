@extends('cms.parent')

@section('title', 'تعديل مستخدم')
@section('page-title', 'تعديل بيانات المستخدم')

@php
    $typeLabels = [
        'admin'  => ['label' => 'مدير', 'color' => 'danger',   'icon' => 'bi-shield-fill-check'],
        'lawyer' => ['label' => 'محامي','color' => 'primary',  'icon' => 'bi-briefcase-fill'],
        'client' => ['label' => 'موكل', 'color' => 'info',     'icon' => 'bi-person-fill'],
        'staff'  => ['label' => 'موظف', 'color' => 'secondary','icon' => 'bi-person-badge-fill'],
    ];
    $type = $typeLabels[$user->user_type] ?? ['label' => $user->user_type, 'color' => 'light', 'icon' => 'bi-person'];
@endphp

@section('content')
<div class="row">

    {{-- شريط علوي --}}
    <div class="col-12 mb-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <span class="avatar-initial me-3" style="width:50px;height:50px;font-size:1.4rem;">
                        {{ mb_substr($user->name, 0, 1) }}
                    </span>
                    <div>
                        <small class="text-white-50 d-block mb-1">
                            <i class="bi bi-pencil-square me-1"></i> تعديل بيانات المستخدم
                        </small>
                        <h5 class="mb-0 fw-bold">{{ $user->name }}</h5>
                        <small class="text-white-50">{{ $user->email }}</small>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge text-bg-{{ $type['color'] }} status-badge mb-2">
                        <i class="bi {{ $type['icon'] }} me-1"></i>{{ $type['label'] }}
                    </span>
                    @if ($user->is_active)
                        <span class="badge text-bg-success status-badge">
                            <i class="bi bi-check-circle-fill me-1"></i>مفعّل
                        </span>
                    @else
                        <span class="badge text-bg-danger status-badge">
                            <i class="bi bi-x-circle-fill me-1"></i>موقوف
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- النموذج --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square text-warning me-2"></i>
                    تعديل البيانات
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- الاسم --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-muted me-1"></i>
                                الاسم الكامل <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   maxlength="45"
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
                                   value="{{ old('email', $user->email) }}"
                                   maxlength="45"
                                   dir="ltr"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- كلمة المرور الجديدة --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock-fill text-muted me-1"></i>
                                كلمة مرور جديدة <small class="text-muted">(اتركها فارغة لعدم التغيير)</small>
                            </label>
                            <input type="password"
                                   name="password"
                                   minlength="8"
                                   placeholder="اتركها فارغة لعدم التغيير"
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
                                تأكيد كلمة المرور
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   placeholder="أعد كتابة كلمة المرور الجديدة"
                                   class="form-control">
                        </div>

                        {{-- الهاتف --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone-fill text-muted me-1"></i>
                                رقم الهاتف <small class="text-muted">(اختياري)</small>
                            </label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   maxlength="45"
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
                                @foreach (['admin' => 'مدير النظام', 'lawyer' => 'محامي', 'client' => 'موكل', 'staff' => 'موظف إداري'] as $val => $label)
                                    <option value="{{ $val }}" @selected(old('user_type', $user->user_type) === $val)>
                                        {{ $label }}
                                    </option>
                                @endforeach
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
                                <option value="1" @selected(old('is_active', $user->is_active) == 1)>مفعّل</option>
                                <option value="0" @selected(old('is_active', $user->is_active) == 0)>موقوف</option>
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
                                                       @checked(in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())))>
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
                            <i class="bi bi-check-lg"></i> حفظ التعديلات
                        </button>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> إلغاء
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- البطاقة الجانبية --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle-fill text-warning me-1"></i>
                    معلومات المستخدم
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">الاسم</span>
                    <span class="fw-semibold text-end">{{ $user->name }}</span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">البريد</span>
                    <span class="fw-semibold text-end" style="font-family: monospace; font-size:0.75rem;">
                        {{ $user->email }}
                    </span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">النوع</span>
                    <span class="badge text-bg-{{ $type['color'] }} status-badge">
                        <i class="bi {{ $type['icon'] }} me-1"></i>{{ $type['label'] }}
                    </span>
                </div>
                <div class="p-3 d-flex justify-content-between small">
                    <span class="text-muted">عدد الأدوار</span>
                    <span class="fw-semibold">{{ $user->roles->count() }}</span>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> عرض التفاصيل الكاملة
                </a>
            </div>
        </div>

        {{-- تحذير --}}
        <div class="card border-0 mt-3" style="background: #fff5f5;">
            <div class="card-body">
                <h6 class="fw-bold text-danger mb-2">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    تنبيه
                </h6>
                <p class="small text-muted mb-0">
                    تغيير <strong>نوع المستخدم</strong> سيؤثر على صلاحياته. تأكد قبل الحفظ.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection