@extends('cms.parent')

@section('title', 'العقد '.$contract->contract_number ?? $contract->id)
@section('page-title', 'ملف العقد رقم '.$contract->id)

@php
    $statusColors = ['نشط' => 'success', 'منتهي' => 'secondary', 'ملغي' => 'danger'];
    $fileIcons = [
        'pdf' => 'bi-file-earmark-pdf text-danger',
        'doc' => 'bi-file-earmark-word text-primary', 'docx' => 'bi-file-earmark-word text-primary',
        'jpg' => 'bi-file-earmark-image text-success', 'jpeg' => 'bi-file-earmark-image text-success', 'png' => 'bi-file-earmark-image text-success',
    ];
@endphp

@section('content')
<div class="row g-3">
    {{-- Top row: contract basics + documents --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold">بيانات العقد</span>
                <span class="badge rounded-pill text-bg-{{ $statusColors[$contract->contract_status] ?? 'light' }}">{{ $contract->contract_status }}</span>
            </div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">نوع العقد</dt><dd class="col-7">{{ $contract->contract_type }}</dd>
                    <dt class="col-5 text-muted">الموكل</dt><dd class="col-7">{{ $contract->client?->user?->name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">المحامي المسؤول</dt><dd class="col-7">{{ $contract->lawyer?->name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">الأطراف</dt><dd class="col-7">{{ $contract->parties }}</dd>
                    <dt class="col-5 text-muted">قيمة العقد</dt><dd class="col-7 fw-bold">{{ number_format($contract->contract_value, 2) }}</dd>
                    <dt class="col-5 text-muted">تاريخ التوقيع</dt><dd class="col-7">{{ optional($contract->signed_at)->format('Y-m-d') }}</dd>
                </dl>
            </div>
            <div class="card-footer bg-white">
            <a href="{{ route('contracts.pdf', $contract) }}" class="btn btn-sm btn-light">
                <i class="bi bi-file-pdf text-danger"></i> PDF
            </a>
            <a href="{{ route('contracts.invoice', $contract) }}" class="btn btn-sm btn-light">
                <i class="bi bi-receipt text-warning"></i> فاتورة
            </a>
            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> تعديل العقد</a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white fw-bold"><i class="bi bi-file-earmark-arrow-up"></i> مستندات العقد الممسوحة ضوئيًا ({{ $contract->attachments->count() }})</div>
            <div class="card-body">
                <form id="uploadForm" action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="related_id" value="{{ $contract->id }}">
                    <input type="hidden" name="related_type" value="contract">
                    <div id="dropZone" class="border border-2 border-dashed rounded-3 text-center p-3 mb-3 text-muted" style="cursor:pointer;">
                        <i class="bi bi-cloud-arrow-up fs-3"></i>
                        <div class="small">اسحب وأفلت المستند الممسوح هنا، أو اضغط للاختيار</div>
                        <input type="file" name="file" id="fileInput" class="d-none" required>
                    </div>
                </form>
                <div class="row row-cols-3 row-cols-md-4 g-2">
                    @forelse ($contract->attachments as $doc)
                        <div class="col">
                            <div class="border rounded-3 p-2 text-center h-100 position-relative">
                                <form action="{{ route('attachments.destroy', $doc) }}" method="POST" class="position-absolute top-0 end-0"
                                      onsubmit="return confirm('حذف المستند؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-x-circle"></i></button>
                                </form>
                                <i class="bi {{ $fileIcons[$doc->file_type] ?? 'bi-file-earmark' }} fs-3"></i>
                                <div class="small text-truncate">{{ basename($doc->file_url) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted text-center small py-3">لا توجد مستندات ممسوحة بعد.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom row: financial ledger --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white fw-bold"><i class="bi bi-receipt-cutoff"></i> السجل المالي للعقد</div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center bg-light">
                            <div class="text-muted small">قيمة العقد</div>
                            <div class="fs-4 fw-bold">{{ number_format($contract->contract_value, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center bg-success bg-opacity-10">
                            <div class="text-muted small">إجمالي المدفوع</div>
                            <div class="fs-4 fw-bold text-success">{{ number_format($totalPaid, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center bg-danger bg-opacity-10">
                            <div class="text-muted small">الرصيد المتبقي</div>
                            <div class="fs-4 fw-bold text-danger">{{ number_format($remaining, 2) }}</div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>التاريخ</th><th>النوع</th><th>المبلغ</th><th>ملاحظات</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr>
                                    <td>{{ optional($payment->transaction_date)->format('Y-m-d') }}</td>
                                    <td><span class="badge text-bg-light">{{ $payment->transaction_type }}</span></td>
                                    <td class="fw-semibold">{{ number_format($payment->amount, 2) }}</td>
                                    <td class="text-muted small">{{ $payment->notes }}</td>
                                    <td>
                                        <form action="{{ route('financial-records.destroy', $payment) }}" method="POST"
                                              onsubmit="return confirm('حذف هذه الحركة المالية؟');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">لا توجد دفعات مسجلة بعد.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Instant receipt logging form --}}
                <form action="{{ route('financial-records.store') }}" method="POST" class="row g-2 border-top pt-3 mt-2">
                    @csrf
                    <input type="hidden" name="clients_id" value="{{ $contract->clients_id }}">
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="المبلغ" required>
                    </div>
                    <div class="col-md-3">
                        <select name="transaction_type" class="form-select" required>
                            <option value="دفعة عقد" selected>دفعة عقد</option>
                            <option value="رسوم قضية">رسوم قضية</option>
                            <option value="مصروف">مصروف</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="transaction_date" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> تسجيل إيصال</button>
                    </div>
                    <div class="col-12">
                        <input type="text" name="notes" class="form-control" placeholder="ملاحظات (اختياري)">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadForm = document.getElementById('uploadForm');

    dropZone.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', () => { if (fileInput.files.length) uploadForm.submit(); });

    ['dragenter', 'dragover'].forEach(evt =>
        dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.classList.add('border-primary', 'bg-light'); })
    );
    ['dragleave', 'drop'].forEach(evt =>
        dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.classList.remove('border-primary', 'bg-light'); })
    );
    dropZone.addEventListener('drop', e => {
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            uploadForm.submit();
        }
    });
</script>
@endpush
@endsection
