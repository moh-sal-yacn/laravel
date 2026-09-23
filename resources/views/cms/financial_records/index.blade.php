@extends('cms.parent')
@section('title', 'السجلات المالية')
@section('page-title', 'السجلات المالية العامة')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white fw-bold">قائمة الحركات المالية</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>التاريخ</th><th>النوع</th><th>المبلغ</th><th>الموكل</th><th>القضية</th><th>سجّلها</th><th class="text-center">الإجراءات</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td>{{ optional($record->transaction_date)->format('Y-m-d') }}</td>
                                    <td><span class="badge text-bg-light">{{ $record->transaction_type }}</span></td>
                                    <td class="fw-semibold">{{ number_format($record->amount, 2) }}</td>
                                    <td>{{ $record->client?->user?->name ?? '—' }}</td>
                                    <td>{{ $record->case?->case_number ?? '—' }}</td>
                                    <td>{{ $record->recordedBy?->name ?? '—' }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('financial-records.destroy', $record) }}" method="POST" onsubmit="return confirm('حذف هذا السجل؟');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">لا توجد سجلات مالية.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($records->hasPages())<div class="card-footer bg-white">{{ $records->links() }}</div>@endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white fw-bold">تسجيل حركة مالية</div>
            <div class="card-body">
                <form action="{{ route('financial-records.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">المبلغ</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">النوع</label>
                        <select name="transaction_type" class="form-select" required>
                            <option value="دفعة عقد">دفعة عقد</option>
                            <option value="رسوم قضية">رسوم قضية</option>
                            <option value="مصروف">مصروف</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="transaction_date" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الموكل (اختياري)</label>
                        <select name="clients_id" class="form-select">
                            <option value="">— بدون —</option>
                            @foreach ($clients as $client)<option value="{{ $client->id }}">{{ $client->user?->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">القضية (اختياري)</label>
                        <select name="cases_id" class="form-select">
                            <option value="">— بدون —</option>
                            @foreach ($cases as $case)<option value="{{ $case->id }}">{{ $case->case_number }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button class="btn btn-primary w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
