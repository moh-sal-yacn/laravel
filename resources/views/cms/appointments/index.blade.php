@extends('cms.parent')
@section('title', 'المواعيد')
@section('page-title', 'إدارة المواعيد')

@php $statusColors = ['مجدول' => 'warning', 'مكتمل' => 'success', 'ملغي' => 'danger']; @endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-bold">قائمة المواعيد</span>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> موعد جديد</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>التاريخ والوقت</th><th>المحامي</th><th>الموكل</th><th>الحجز المرتبط</th><th>الحالة</th><th class="text-center">الإجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td class="fw-semibold">{{ $appointment->appointment_date->format('Y-m-d H:i') }}</td>
                            <td>{{ $appointment->lawyer?->name ?? '—' }}</td>
                            <td>{{ $appointment->client?->user?->name ?? '—' }}</td>
                            <td>{{ $appointment->booking?->visitor_name ?? '—' }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ $statusColors[$appointment->status] ?? 'light' }}">{{ $appointment->status }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذا الموعد؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">لا توجد مواعيد مسجلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($appointments->hasPages())<div class="card-footer bg-white">{{ $appointments->links() }}</div>@endif
</div>
@endsection
