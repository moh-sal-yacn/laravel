@extends('cms.parent')
@section('title', 'العقود')
@section('page-title', 'إدارة العقود')

@php $statusColors = ['نشط' => 'success', 'منتهي' => 'secondary', 'ملغي' => 'danger']; @endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-bold">قائمة العقود</span>
        <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> عقد جديد</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>نوع العقد</th><th>الموكل</th><th>المحامي</th><th>القيمة</th><th>الحالة</th><th>تاريخ التوقيع</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($contracts as $contract)
                        <tr>
                            <td class="fw-semibold">{{ $contract->contract_type }}</td>
                            <td>{{ $contract->client?->user?->name ?? '—' }}</td>
                            <td>{{ $contract->lawyer?->name ?? '—' }}</td>
                            <td>{{ number_format($contract->contract_value, 2) }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ $statusColors[$contract->contract_status] ?? 'light' }}">{{ $contract->contract_status }}</span></td>
                            <td>{{ optional($contract->signed_at)->format('Y-m-d') }}</td>
                            <td class="text-center">
                                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذا العقد؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">لا توجد عقود مسجلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($contracts->hasPages())<div class="card-footer bg-white">{{ $contracts->links() }}</div>@endif
</div>
@endsection
