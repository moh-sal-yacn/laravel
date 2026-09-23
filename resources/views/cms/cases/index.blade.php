@extends('cms.parent')

@section('title', 'القضايا')
@section('page-title', 'إدارة القضايا')

@php
    $statusColors = [
        'قيد النظر' => 'warning',
        'مؤجلة' => 'secondary',
        'منتهية' => 'success',
        'مؤرشفة' => 'dark',
    ];
@endphp

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <span class="fw-bold">قائمة القضايا</span>
            <a href="{{ route('cases.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> قضية جديدة
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>رقم القضية</th>
                            <th>عنوان القضية</th>
                            <th>الموكل</th>
                            <th>المحكمة</th>
                            <th>الحالة</th>
                            <th>تاريخ الفتح</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cases as $case)
                            <tr>
                                <td class="fw-semibold">{{ $case->case_number }}</td>
                                <td>{{ $case->case_title }}</td>
                                <td>{{ $case->client?->user?->name ?? '—' }}</td>
                                <td>{{ $case->court?->court_name ?? '—' }}</td>
                                <td>
                                    <span class="badge rounded-pill text-bg-{{ $statusColors[$case->case_status] ?? 'light' }} status-badge">
                                        {{ $case->case_status }}
                                    </span>
                                </td>
                                <td>{{ optional($case->opened_at)->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('cases.show', $case) }}" class="btn btn-sm btn-outline-info" title="التفاصيل">
                                        <i class="bi bi-eye"></i> التفاصيل
                                    </a>
                                    <a href="{{ route('cases.edit', $case) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('cases.destroy', $case) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه القضية؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">لا توجد قضايا مسجلة حتى الآن.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($cases->hasPages())
            <div class="card-footer bg-white">{{ $cases->links() }}</div>
        @endif
    </div>
@endsection
