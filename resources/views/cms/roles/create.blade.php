@extends('cms.parent')
@section('title', 'دور جديد')
@section('page-title', 'إضافة دور جديد')

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

    {{-- النموذج --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-shield-plus text-warning me-2"></i>
                    بيانات الدور الجديد
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">

                        {{-- اسم الدور --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-shield-fill text-muted me-1"></i>
                                اسم الدور <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="role_name"
                                   value="{{ old('role_name') }}"
                                   maxlength="45"
                                   placeholder="مثال: محامي أول، مساعد إداري..."
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
                                      placeholder="وصف مختصر لمهام هذا الدور..."
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
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
                                الصلاحيات <small class="text-muted">(اختر صلاحية أو أكثر)</small>
                            </label>

                            @php
                                // تجميع الصلاحيات حسب الوحدة
                                $grouped = $permissions->groupBy('module');
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
                                                               @checked(in_array($permission->id, old('permissions', [])))>
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
                            <i class="bi bi-check-lg"></i> حفظ الدور
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
        <div class="card border-0" style="background: linear-gradient(135deg, #fffbf0 0%, #fff5d6 100%);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                    إرشادات سريعة
                </h6>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>اسم الدور</strong>: واضح ومميز (محامي، موظف إداري...).
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>الصلاحيات</strong>: اختر ما يناسب مهام الدور.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>الوصف</strong>: يساعد في معرفة مهام الدور.
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body text-center">
                <i class="bi bi-key-fill fs-1 text-warning"></i>
                <div class="fs-3 fw-bold mt-2">{{ $permissions->count() }}</div>
                <small class="text-muted">صلاحية متاحة</small>
            </div>
        </div>
    </div>

</div>
@endsection