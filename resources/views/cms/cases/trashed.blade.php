@extends('cms.parent')

@section('title', 'القضايا المحذوفة')
@section('page-title', 'القضايا المحذوفة')

@php
    $statusColors = [
        'قيد النظر' => 'warning',
        'مؤجلة'    => 'secondary',
        'منتهية'   => 'success',
        'مؤرشفة'   => 'dark',
    ];
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
        <div>
            <h5 class="mb-0 fw-bold text-danger">
                <i class="bi bi-trash2-fill me-2"></i>
                القضايا المحذوفة
            </h5>
            <small class="text-muted">إجمالي: {{ $cases->total() }} قضية</small>
        </div>
        <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> رجوع للقضايا
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">رقم القضية</th>
                        <th>عنوان القضية</th>
                        <th>الموكل</th>
                        <th>الحالة</th>
                        <th>تاريخ الحذف</th>
                        <th class="text-center pe-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cases as $case)
                        <tr class="table-danger">
                            <td class="ps-3">
                                <span class="badge text-bg-dark" style="font-family: monospace;">
                                    {{ $case->case_number }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($case->case_title, 50) }}</div>
                            </td>
                            <td>
                                @if ($case->client?->user?->name)
                                    <span class="avatar-initial">{{ mb_substr($case->client->user->name, 0, 1) }}</span>
                                    {{ $case->client->user->name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-{{ $statusColors[$case->case_status] ?? 'light' }} status-badge">
                                    {{ $case->case_status }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-clock-history text-muted me-1"></i>
                                {{ $case->deleted_at->translatedFormat('d M Y - h:i A') }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group" role="group">
                                    <form action="{{ route('cases.restore', $case->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-arrow-counterclockwise"></i> استرجاع
                                        </button>
                                    </form>
                                    <form action="{{ route('cases.force-delete', $case->id) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('⚠️ هل أنت متأكد؟ هذا الحذف نهائي!');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-circle-fill"></i> حذف نهائي
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-check-circle-fill fs-1 d-block mb-2 text-success"></i>
                                <p class="mb-0">لا توجد قضايا محذوفة.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($cases->hasPages())
        <div class="card-footer bg-white border-0">{{ $cases->links() }}</div>
    @endif
</div>
@endsection