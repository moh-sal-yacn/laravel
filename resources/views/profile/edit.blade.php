@extends('cms.parent')

@section('title', 'الملف الشخصي')
@section('page-title', 'الملف الشخصي')

@php
    $user = auth()->user();
    $currentRole = $user->roles->first();
@endphp

@section('content')

    {{-- ═══════════════════════════════════════════ --}}
    {{--                  رأس الصفحة                  --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="card mb-4 border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="avatar-initial me-3" style="width:72px;height:72px;font-size:2rem;">
                    {{ mb_substr($user->name, 0, 1) }}
                </span>
                <div>
                    <small class="text-white-50 d-block mb-1">
                        <i class="bi bi-person-badge me-1"></i>
                        الملف الشخصي
                    </small>
                    <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                    <div class="text-white-50 small" dir="ltr">
                        <i class="bi bi-envelope me-1"></i>
                        {{ $user->email }}
                    </div>
                </div>
            </div>
            <div class="text-end">
                @if ($currentRole)
                    <span class="badge text-bg-warning status-badge mb-2">
                        <i class="bi bi-shield-fill me-1"></i>
                        {{ $currentRole->role_name }}
                    </span>
                @endif
                <div class="text-white-50 small">
                    <i class="bi bi-calendar3 me-1"></i>
                    عضو منذ {{ $user->created_at->translatedFormat('F Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- ═══════════════════════════════════════════ --}}
        {{--           معلومات الحساب (الاسم + البريد)      --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle text-warning me-2"></i>
                        معلومات الحساب
                    </h5>
                    <small class="text-muted">
                        حدّث اسمك وبريدك الإلكتروني.
                    </small>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" novalidate>
                        @csrf
                        @method('PATCH')

                        {{-- الاسم --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-muted me-1"></i>
                                الاسم الكامل <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   autofocus
                                   autocomplete="name"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- البريد الإلكتروني --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill text-muted me-1"></i>
                                البريد الإلكتروني <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   autocomplete="username"
                                   dir="ltr"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- زر الحفظ --}}
                        <div class="d-flex gap-2 align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> حفظ التعديلات
                            </button>

                            @if (session('status') === 'profile-updated')
                                <span class="text-success small">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    تم الحفظ بنجاح.
                                </span>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{--               تغيير كلمة المرور                --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-shield-lock-fill text-warning me-2"></i>
                        تغيير كلمة المرور
                    </h5>
                    <small class="text-muted">
                        استخدم كلمة مرور قوية وطويلة للحفاظ على أمان حسابك.
                    </small>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- كلمة المرور الحالية --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">
                                <i class="bi bi-lock-fill text-muted me-1"></i>
                                كلمة المرور الحالية <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   autocomplete="current-password"
                                   dir="ltr"
                                   class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif">
                            @if ($errors->updatePassword->has('current_password'))
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $errors->updatePassword->first('current_password') }}
                                </div>
                            @endif
                        </div>

                        {{-- كلمة المرور الجديدة --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="bi bi-key-fill text-muted me-1"></i>
                                كلمة المرور الجديدة <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   autocomplete="new-password"
                                   dir="ltr"
                                   class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif">
                            @if ($errors->updatePassword->has('password'))
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $errors->updatePassword->first('password') }}
                                </div>
                            @endif
                        </div>

                        {{-- تأكيد كلمة المرور --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                <i class="bi bi-key-fill text-muted me-1"></i>
                                تأكيد كلمة المرور <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   autocomplete="new-password"
                                   dir="ltr"
                                   class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif">
                            @if ($errors->updatePassword->has('password_confirmation'))
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $errors->updatePassword->first('password_confirmation') }}
                                </div>
                            @endif
                        </div>

                        {{-- تلميح --}}
                        <div class="alert alert-info border-0 small mb-3">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            <strong>متطلبات:</strong>
                            8 أحرف على الأقل، يُفضَّل استخدام أرقام ورموز.
                        </div>

                        {{-- زر الحفظ --}}
                        <div class="d-flex gap-2 align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-shield-check"></i> تحديث كلمة المرور
                            </button>

                            @if (session('status') === 'password-updated')
                                <span class="text-success small">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    تم تحديث كلمة المرور.
                                </span>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{--               حذف الحساب (خطير!)               --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);">
                <div class="card-body">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-8">
                            <h5 class="fw-bold text-danger mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                حذف الحساب
                            </h5>
                            <p class="small text-muted mb-0">
                                بمجرد حذف حسابك، سيتم حذف جميع بياناتك نهائياً ولا يمكن استعادتها.
                                <br>
                                <strong class="text-danger">هذا الإجراء لا يمكن التراجع عنه.</strong>
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <button type="button"
                                    class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteAccountModal">
                                <i class="bi bi-trash-fill"></i> حذف الحساب نهائياً
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{--          Modal تأكيد حذف الحساب              --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('profile.destroy') }}" novalidate>
                    @csrf
                    @method('DELETE')

                    <div class="modal-header border-0 bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            تأكيد حذف الحساب
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            هل أنت متأكد أنك تريد حذف حسابك؟ هذا الإجراء <strong class="text-danger">لا يمكن التراجع عنه</strong>.
                            <br>
                            أدخل كلمة المرور للتأكيد.
                        </p>

                        <label for="delete_password" class="form-label fw-semibold small">
                            <i class="bi bi-lock-fill text-muted me-1"></i>
                            كلمة المرور
                        </label>
                        <input type="password"
                               id="delete_password"
                               name="password"
                               dir="ltr"
                               placeholder="••••••••"
                               class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif">
                        @if ($errors->userDeletion->has('password'))
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $errors->userDeletion->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash-fill"></i> نعم، احذف حسابي
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection