@extends('cms.parent')
@section('title', 'المواعيد')
@section('page-title', 'إدارة المواعيد')

@php
    $statusColors = [
        'مجدول' => 'warning',
        'مكتمل' => 'success',
        'ملغي'  => 'danger',
    ];
    $statusIcons = [
        'مجدول' => 'bi-clock-history',
        'مكتمل' => 'bi-check-circle-fill',
        'ملغي'  => 'bi-x-circle-fill',
    ];
@endphp

@section('content')

    {{-- ═══════════ Filter Bar ═══════════ --}}
    @include('cms.partials.filter-bar', [
        'action' => route('appointments.index'),
        'filters' => [
            [
                'name' => 'search',
                'type' => 'text',
                'label' => 'بحث',
                'placeholder' => 'المحامي، الموكل، الملاحظات...',
                'icon' => 'bi-search',
                'col' => 'col-md-3',
            ],
            [
                'name' => 'status',
                'type' => 'select',
                'label' => 'الحالة',
                'options' => [
                    'مجدول' => 'مجدول',
                    'مكتمل' => 'مكتمل',
                    'ملغي'  => 'ملغي',
                ],
                'icon' => 'bi-flag',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'lawyer',
                'type' => 'select',
                'label' => 'المحامي',
                'options' => $lawyers->pluck('name', 'id')->toArray(),
                'icon' => 'bi-briefcase-fill',
                'col' => 'col-md-3',
            ],
            [
                'name' => 'client',
                'type' => 'select',
                'label' => 'الموكل',
                'options' => $clients->mapWithKeys(fn ($c) => [$c->id => $c->user?->name ?? 'موكل #' . $c->id])->toArray(),
                'icon' => 'bi-person-fill',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'date',
                'type' => 'date_range',
                'label' => 'تاريخ الموعد',
                'icon' => 'bi-calendar3',
                'col' => 'col-md-2',
            ],
        ],
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-calendar-check-fill text-warning me-2"></i>
                    قائمة المواعيد
                </h5>
                <small class="text-muted">إجمالي: {{ $appointments->total() }} موعد</small>
            </div>
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> موعد جديد
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">التاريخ والوقت</th>
                            <th>المحامي</th>
                            <th>الموكل</th>
                            <th>الحجز المرتبط</th>
                            <th>الحالة</th>
                            <th class="text-center pe-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-event text-warning fs-5 me-2"></i>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $appointment->appointment_date->translatedFormat('d M Y') }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i>
                                                {{ $appointment->appointment_date->format('h:i A') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($appointment->lawyer?->name)
                                        <div class="d-flex align-items-center">
                                            <span class="avatar-initial">
                                                {{ mb_substr($appointment->lawyer->name, 0, 1) }}
                                            </span>
                                            <span>{{ $appointment->lawyer->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($appointment->client?->user?->name)
                                        <div class="d-flex align-items-center">
                                            <span class="avatar-initial" style="background:linear-gradient(135deg,#4facfe,#00f2fe);color:#fff;">
                                                {{ mb_substr($appointment->client->user->name, 0, 1) }}
                                            </span>
                                            <span>{{ $appointment->client->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($appointment->booking?->visitor_name)
                                        <span class="badge text-bg-light border">
                                            <i class="bi bi-person-badge me-1"></i>
                                            {{ $appointment->booking->visitor_name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill text-bg-{{ $statusColors[$appointment->status] ?? 'light' }} status-badge">
                                        <i class="bi {{ $statusIcons[$appointment->status] ?? 'bi-circle' }} me-1"></i>
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="text-center pe-3">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info" title="التفاصيل">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('حذف هذا الموعد؟');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-0">لا توجد مواعيد مسجلة.</p>
                                    <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="bi bi-plus-lg"></i> إضافة أول موعد
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($appointments->hasPages())
            <div class="card-footer bg-white border-0">{{ $appointments->links() }}</div>
        @endif
    </div>
@endsection