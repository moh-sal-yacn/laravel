@extends('cms.parent')

@section('title', 'الموكلون المحذوفون')
@section('page-title', 'الموكلون المحذوفون')

@php
    $kindColors = [
        'individual' => ['label' => 'فرد',  'color' => 'info',    'icon' => 'bi-person'],
        'company'    => ['label' => 'شركة', 'color' => 'primary', 'icon' => 'bi-building'],
    ];
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
        <div>
            <h5 class="mb-0 fw-bold text-danger">
                <i class="bi bi-trash2-fill me-2"></i>
                الموكلون المحذوفون
            </h5>
            <small class="text-muted">إجمالي: {{ $clients->total() }} موكل</small>
        </div>
        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> رجوع للموكلين
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">الاسم</th>
                        <th>البريد</th>
                        <th>النوع</th>
                        <th>الرقم الوطني</th>
                        <th>تاريخ الحذف</th>
                        <th class="text-center pe-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        @php
                            $kind = $kindColors[$client->client_kind] ?? ['label' => $client->client_kind, 'color' => 'light', 'icon' => 'bi-question'];
                        @endphp
                        <tr class="table-danger">
                            <td class="ps-3">
                                @if ($client->user?->name)
                                    <span class="avatar-initial">{{ mb_substr($client->user->name, 0, 1) }}</span>
                                    <span class="fw-semibold">{{ $client->user->name }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($client->user?->email)
                                    <span dir="ltr" style="font-size:.8rem;">{{ $client->user->email }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $kind['color'] }} status-badge">
                                    <i class="bi {{ $kind['icon'] }} me-1"></i>{{ $kind['label'] }}
                                </span>
                            </td>
                            <td>
                                <span style="font-family: monospace;">{{ $client->national_id ?? '—' }}</span>
                            </td>
                            <td>
                                <i class="bi bi-clock-history text-muted me-1"></i>
                                {{ $client->deleted_at->translatedFormat('d M Y - h:i A') }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group" role="group">
                                    <form action="{{ route('clients.restore', $client->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-arrow-counterclockwise"></i> استرجاع
                                        </button>
                                    </form>
                                    <form action="{{ route('clients.force-delete', $client->id) }}" method="POST"
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
                                <p class="mb-0">لا يوجد موكلون محذوفون.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($clients->hasPages())
        <div class="card-footer bg-white border-0">{{ $clients->links() }}</div>
    @endif
</div>
@endsection