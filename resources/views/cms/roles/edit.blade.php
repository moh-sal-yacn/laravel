@extends('cms.parent')
@section('title', 'تعديل دور')
@section('page-title', 'تعديل الدور')

@php
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
<div class="row">

    {{-- شريط علوي --}}
    <div class="col-12 mb-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
                <div>
                    <small class="text-white-50 d-block mb-1">
                        <i class="bi bi-pencil-square me-1"></i> تعديل الدور
                    </small>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-shield-fill text-warning me-2"></i>
                        {{ $role->role_name }}
                    </h5>
                </div>
                <div class="text-end">
                    <span class="badge text-bg-info status-badge">
                        <i class="bi bi-key me-1"></i>
                        {{ $role->permissions->count() }} صلاحية
                    </span>
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
            </div>

            <div class="card-body">
                <form action="{{ route('roles.update', $role) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- اسم الدور --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-shield-fill text-muted me-1"></i>
                                اسم الدور <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="role_name"
                                   value="{{ old('role_name', $role->role_name) }}"
                                   maxlength="45"
                                   class="form-control @error('role_name') is-invalid @enderror">
                            @error('role_name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الوصف --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-file-text text-muted me-1"></i>
                                الوصف <small class="text-muted">(اختياري)</small>
                            </label>
                            <textarea name="description"
                                      rows="2"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الصلاحيات --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-key-fill text-muted me-1"></i>
                                الصلاحيات
                            </label>

                            @php
                                $grouped = $permissions->groupBy('module');
                                $currentPermissions = old('permissions', $role->permissions->pluck('id')->toArray());
                            @endphp

                            @foreach ($grouped as $module => $items)
                                @php
                                    $mod = $moduleIcons[strtolower($module)] ?? ['icon' => 'bi-grid', 'color' => 'secondary'];
                                @endphp
                                <div class="border rounded-3 mb-2 overflow-hidden">
                                    <div class="bg-light px-3 py-2 fw-semibold small d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="bi {{ $mod['icon'] }} text-{{ $mod['color'] }} me-1"></i>
                                            {{ $module }}
                                        </span>
                                        <span class="badge text-bg-light">{{ $items->count() }}</span>
                                    </div>
                                    <div class="p-3">
                                        <div class="row">
                                            @foreach ($items as $permission)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $permission->id }}"
                                                               class="form-check-input"
                                                               id="perm{{ $permission->id }}"
                                                               @checked(in_array($permission->id, $currentPermissions))>
                                                        <label class="form-check-label small" for="perm{{ $permission->id }}">
                                                            {{ $permission->permission_name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @error('permissions')
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
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
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
                    معلومات الدور الحالي
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">المعرّف</span>
                    <span class="fw-semibold" style="font-family: monospace;">#{{ $role->id }}</span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">الاسم</span>
                    <span class="fw-semibold text-end">{{ $role->role_name }}</span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">عدد الصلاحيات</span>
                    <span class="badge text-bg-info">{{ $role->permissions->count() }}</span>
                </div>
                <div class="p-3 d-flex justify-content-between small">
                    <span class="text-muted">عدد المستخدمين</span>
                    <span class="fw-semibold">{{ $role->users()->count() }}</span>
                </div>
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
                    تغيير الصلاحيات سيؤثر فوراً على كل المستخدمين الذين يحملون هذا الدور.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection