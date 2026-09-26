@extends('cms.parent')

@section('title', 'العقود المحذوفة')
@section('page-title', 'العقود المحذوفة')

@php
    $statusColors = [
        'نشط'   => 'success',
        'منتهي' => 'secondary',
        'ملغي'  => 'danger',
    ];
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
        <div>
            <h5 class="mb-0 fw-bold text-danger">
                <i class="bi bi-trash2-fill me-2"></i>
                العقود المحذوفة
            </h5>
            <small class="text-muted">إجمالي: {{ $contracts->total() }} عقد</small>
        </div>
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> رجوع للعقود
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">نوع العقد</th>
                        <th>الموكل</th>
                        <th>القيمة</th>
                        <th>الحالة</th>
                        <th>تاريخ الحذف</th>
                        <th class="text-center pe-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contracts as $contract)
                        <tr class="table-danger">
                            <td class="ps-3">
                                <div class="fw-semibold">{{ $contract->contract_type }}</div>
                            </td>
                            <td>
                                @if ($contract->client?->user?->name)
                                    <span class="avatar-initial">{{ mb_substr($contract->client->user->name, 0, 1) }}</span>
                                    {{ $contract->client->user->name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($contract->contract_value, 2) }}</span>
                                <small class="text-muted">₪</small>
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-{{ $statusColors[$contract->contract_status] ?? 'light' }} status-badge">
                                    {{ $contract->contract_status }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-clock-history text-muted me-1"></i>
                                {{ $contract->deleted_at->translatedFormat('d M Y - h:i A') }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group" role="group">
                                    <form action="{{ route('contracts.restore', $contract->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-arrow-counterclockwise"></i> استرجاع
                                        </button>
                                    </form>
                                    <form action="{{ route('contracts.force-delete', $contract->id) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('⚠️ حذف نهائي؟');">
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
                                <p class="mb-0">لا توجد عقود محذوفة.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($contracts->hasPages())
        <div class="card-footer bg-white border-0">{{ $contracts->links() }}</div>
    @endif
</div>
@endsection