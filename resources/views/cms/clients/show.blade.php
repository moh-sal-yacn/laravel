@extends('cms.parent')
@section('title', $client->user?->name)
@section('page-title', 'ملف الموكل')

@php
    $kindColors = [
        'individual' => ['label' => 'فرد',  'color' => 'info',    'icon' => 'bi-person'],
        'company'    => ['label' => 'شركة', 'color' => 'primary', 'icon' => 'bi-building'],
    ];
    $kind = $kindColors[$client->client_kind] ?? ['label' => $client->client_kind, 'color' => 'light', 'icon' => 'bi-question'];

    $statusColors = [
        'قيد النظر' => 'warning',
        'مؤجلة'    => 'secondary',
        'منتهية'   => 'success',
        'مؤرشفة'   => 'dark',
    ];
    $contractColors = [
        'نشط'   => 'success',
        'منتهي' => 'secondary',
        'ملغي'  => 'danger',
    ];
@endphp

@section('content')

    {{-- ═══════ شريط علوي: بيانات الموكل ═══════ --}}
    <div class="card mb-3 border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="avatar-initial me-3" style="width:64px;height:64px;font-size:1.8rem;">
                    {{ mb_substr($client->user?->name ?? '?', 0, 1) }}
                </span>
                <div>
                    <small class="text-white-50 d-block mb-1">
                        <i class="bi bi-person-badge me-1"></i> ملف الموكل
                    </small>
                    <h4 class="mb-1 fw-bold">{{ $client->user?->name }}</h4>
                    <div class="text-white-50 small">
                        <i class="bi bi-envelope me-1"></i>
                        <span dir="ltr">{{ $client->user?->email }}</span>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <span class="badge text-bg-{{ $kind['color'] }} status-badge mb-2">
                    <i class="bi {{ $kind['icon'] }} me-1"></i>{{ $kind['label'] }}
                </span>
                <div>
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> تعديل
                    </a>
                    <a href="{{ route('clients.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-arrow-right"></i> رجوع
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- ═══════ لوحة يسار: بيانات + إحصاءات ═══════ --}}
        <div class="col-lg-4">

            {{-- بطاقات إحصائية صغيرة --}}
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <div class="card text-center py-3">
                        <i class="bi bi-folder2-open fs-3 text-warning"></i>
                        <div class="fs-4 fw-bold mt-1">{{ $client->cases->count() }}</div>
                        <small class="text-muted">قضية</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card text-center py-3">
                        <i class="bi bi-file-earmark-text fs-3 text-info"></i>
                        <div class="fs-4 fw-bold mt-1">{{ $client->contracts->count() }}</div>
                        <small class="text-muted">عقد</small>
                    </div>
                </div>
            </div>

            {{-- بيانات الموكل --}}
            <div class="card">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle-fill text-warning me-2"></i>
                        البيانات
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom d-flex justify-content-between small">
                        <span class="text-muted"><i class="bi bi-person me-1"></i>الاسم</span>
                        <span class="fw-semibold text-end">{{ $client->user?->name ?? '—' }}</span>
                    </div>
                    <div class="p-3 border-bottom d-flex justify-content-between small">
                        <span class="text-muted"><i class="bi bi-envelope me-1"></i>البريد</span>
                        <span class="fw-semibold text-end" dir="ltr" style="font-family: monospace; font-size:.75rem;">
                            {{ $client->user?->email ?? '—' }}
                        </span>
                    </div>
                    <div class="p-3 border-bottom d-flex justify-content-between small">
                        <span class="text-muted"><i class="bi bi-telephone me-1"></i>الهاتف</span>
                        <span class="fw-semibold text-end" dir="ltr">{{ $client->user?->phone ?? '—' }}</span>
                    </div>
                    <div class="p-3 border-bottom d-flex justify-content-between small">
                        <span class="text-muted"><i class="bi bi-person-badge me-1"></i>النوع</span>
                        <span class="badge text-bg-{{ $kind['color'] }} status-badge">
                            <i class="bi {{ $kind['icon'] }} me-1"></i>{{ $kind['label'] }}
                        </span>
                    </div>
                    <div class="p-3 d-flex justify-content-between small">
                        <span class="text-muted"><i class="bi bi-card-text me-1"></i>الرقم الوطني</span>
                        <span class="fw-semibold text-end" style="font-family: monospace;">{{ $client->national_id ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════ لوحة يمين: القضايا + العقود ═══════ --}}
        <div class="col-lg-8">

            {{-- القضايا --}}
            <div class="card mb-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-folder2-open text-warning me-2"></i>
                        القضايا
                    </h5>
                    <span class="badge text-bg-light">{{ $client->cases->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($client->cases as $case)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-folder2 text-warning fs-5 me-2"></i>
                                <div>
                                    <a href="{{ route('cases.show', $case) }}" class="fw-semibold text-decoration-none">
                                        {{ $case->case_number }}
                                    </a>
                                    <div class="small text-muted">
                                        {{ Str::limit($case->case_title, 60) }}
                                    </div>
                                </div>
                            </div>
                            <span class="badge rounded-pill text-bg-{{ $statusColors[$case->case_status] ?? 'light' }} status-badge">
                                {{ $case->case_status }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                            لا توجد قضايا لهذا الموكل.
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- العقود --}}
            <div class="card">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-file-earmark-text text-warning me-2"></i>
                        العقود
                    </h5>
                    <span class="badge text-bg-light">{{ $client->contracts->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($client->contracts as $contract)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-text text-info fs-5 me-2"></i>
                                <div>
                                    <a href="{{ route('contracts.show', $contract) }}" class="fw-semibold text-decoration-none">
                                        {{ $contract->contract_type }}
                                    </a>
                                    <div class="small text-muted">
                                        <i class="bi bi-cash-coin"></i>
                                        {{ number_format($contract->contract_value, 2) }} ₪
                                    </div>
                                </div>
                            </div>
                            <span class="badge rounded-pill text-bg-{{ $contractColors[$contract->contract_status] ?? 'light' }} status-badge">
                                {{ $contract->contract_status }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                            لا توجد عقود لهذا الموكل.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

@endsection