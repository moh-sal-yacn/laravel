@extends('cms.parent')
@section('title', 'الصلاحيات')
@section('page-title', 'إدارة الصلاحيات')

@php
    // أيقونات الوحدات حسب الاسم
    $moduleIcons = [
        'cases'        => ['icon' => 'bi-folder2-open',    'color' => 'warning'],
        'contracts'    => ['icon' => 'bi-file-earmark-text','color' => 'info'],
        'users'        => ['icon' => 'bi-person-badge',    'color' => 'danger'],
        'finance'      => ['icon' => 'bi-cash-coin',       'color' => 'success'],
        'sessions'     => ['icon' => 'bi-calendar-check',  'color' => 'primary'],
        'appointments' => ['icon' => 'bi-calendar-plus',   'color' => 'secondary'],
        'clients'      => ['icon' => 'bi-people',          'color' => 'info'],
        'courts'       => ['icon' => 'bi-bank2',           'color' => 'dark'],
        'precedents'   => ['icon' => 'bi-journal-bookmark','color' => 'warning'],
        'articles'     => ['icon' => 'bi-newspaper',       'color' => 'secondary'],
        'reports'      => ['icon' => 'bi-bar-chart-line',  'color' => 'success'],
        'settings'     => ['icon' => 'bi-gear',            'color' => 'dark'],
    ];
@endphp

@section('content')
<div class="row g-3">

    {{-- قائمة الصلاحيات --}}
    <div class="col-lg-8">
        @forelse ($permissions as $module => $items)
            @php
                $mod = $moduleIcons[strtolower($module)] ?? ['icon' => 'bi-grid', 'color' => 'secondary'];
            @endphp
            <div class="card mb-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi {{ $mod['icon'] }} text-{{ $mod['color'] }} me-2"></i>
                        {{ $module }}
                    </h5>
                    <span class="badge text-bg-light">{{ $items->count() }} صلاحية</span>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($items as $permission)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar-initial me-2">
                                    {{ mb_substr($permission->permission_name, 0, 1) }}
                                </span>
                                <div>
                                    <div class="fw-semibold">{{ $permission->permission_name }}</div>
                                    <small class="text-muted">
                                        <i class="bi bi-key"></i> الوحدة:
                                        <code>{{ $permission->module }}</code>
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge text-bg-primary status-badge me-2">
                                    <i class="bi bi-shield-lock me-1"></i>
                                    {{ $permission->roles_count ?? 0 }} دور
                                </span>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('حذف هذه الصلاحية؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-key-fill fs-1 d-block mb-2 text-secondary"></i>
                    <p class="mb-0">لا توجد صلاحيات مسجلة.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- نموذج الإضافة --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-plus-circle-fill text-warning me-2"></i>
                    إضافة صلاحية جديدة
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('permissions.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-key text-muted me-1"></i>
                            اسم الصلاحية <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="permission_name"
                               value="{{ old('permission_name') }}"
                               maxlength="45"
                               placeholder="مثال: إدارة القضايا"
                               class="form-control @error('permission_name') is-invalid @enderror">
                        @error('permission_name')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-grid text-muted me-1"></i>
                            الوحدة (Module) <span class="text-danger">*</span>
                        </label>
                        <select name="module" class="form-select @error('module') is-invalid @enderror">
                            <option value="">— اختر الوحدة —</option>
                            @foreach (['cases' => 'القضايا', 'contracts' => 'العقود', 'users' => 'المستخدمون', 'finance' => 'المالية', 'sessions' => 'الجلسات', 'appointments' => 'المواعيد', 'clients' => 'الموكلون', 'courts' => 'المحاكم', 'precedents' => 'السوابق', 'articles' => 'المقالات', 'reports' => 'التقارير', 'settings' => 'الإعدادات'] as $key => $label)
                                <option value="{{ $key }}" @selected(old('module') === $key)>
                                    {{ $label }} ({{ $key }})
                                </option>
                            @endforeach
                        </select>
                        @error('module')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-primary w-100">
                        <i class="bi bi-check-lg"></i> حفظ الصلاحية
                    </button>
                </form>
            </div>
        </div>

        {{-- بطاقة إحصائية --}}
        <div class="card border-0 mt-3" style="background: linear-gradient(135deg, #fffbf0 0%, #fff5d6 100%);">
            <div class="card-body text-center">
                <i class="bi bi-key-fill fs-1 text-warning"></i>
                <div class="fs-3 fw-bold mt-2">{{ $permissions->flatten()->count() }}</div>
                <small class="text-muted">إجمالي الصلاحيات</small>
            </div>
        </div>
    </div>
</div>
@endsection