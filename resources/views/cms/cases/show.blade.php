@extends('cms.parent')

@section('title', 'ملف القضية '.$case->case_number)
@section('page-title', 'ملف القضية: '.$case->case_title)

@php
    $statusColors = [
        'قيد النظر' => 'warning', 'مؤجلة' => 'secondary',
        'منتهية' => 'success', 'مؤرشفة' => 'dark',
    ];
    $fileIcons = [
        'pdf' => 'bi-file-earmark-pdf text-danger',
        'doc' => 'bi-file-earmark-word text-primary', 'docx' => 'bi-file-earmark-word text-primary',
        'jpg' => 'bi-file-earmark-image text-success', 'jpeg' => 'bi-file-earmark-image text-success', 'png' => 'bi-file-earmark-image text-success',
    ];
@endphp

@section('content')
<div class="row g-3">
    {{-- === Left panel: Case profile === --}}
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold">بيانات القضية</span>
                <span class="badge rounded-pill text-bg-{{ $statusColors[$case->case_status] ?? 'light' }}">{{ $case->case_status }}</span>
            </div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">رقم القضية</dt><dd class="col-7">{{ $case->case_number }}</dd>
                    <dt class="col-5 text-muted">العنوان</dt><dd class="col-7">{{ $case->case_title }}</dd>
                    <dt class="col-5 text-muted">الموكل</dt>
                    <dd class="col-7">
                        <a href="{{ route('clients.show', $case->client) }}">{{ $case->client?->user?->name ?? '—' }}</a>
                    </dd>
                    <dt class="col-5 text-muted">المحكمة</dt><dd class="col-7">{{ $case->court?->court_name }} ({{ $case->court?->jurisdiction }})</dd>
                    <dt class="col-5 text-muted">التصنيف</dt><dd class="col-7">{{ $case->category?->category_name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">تاريخ الفتح</dt><dd class="col-7">{{ optional($case->opened_at)->format('Y-m-d') }}</dd>
                    @if ($case->archived_at)
                        <dt class="col-5 text-muted">تاريخ الأرشفة</dt><dd class="col-7">{{ $case->archived_at->format('Y-m-d') }}</dd>
                    @endif
                </dl>
                @if ($case->description)
                    <hr>
                    <p class="small text-muted mb-0">{{ $case->description }}</p>
                @endif
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('cases.edit', $case) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> تعديل بيانات القضية</a>
            </div>
        </div>

        {{-- Participants --}}
        <div class="card mb-3">
            <div class="card-header bg-white fw-bold">أطراف القضية</div>
            <ul class="list-group list-group-flush">
                @forelse ($case->participants as $participant)
                    <li class="list-group-item d-flex justify-content-between small">
                        <span>{{ $participant->name }}</span>
                        <span class="badge text-bg-light">{{ $participant->pivot->role_in_case }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted small">لا يوجد أطراف مضافون بعد.</li>
                @endforelse
            </ul>
        </div>

        {{-- Sessions timeline --}}
        <div class="card">
            <div class="card-header bg-white fw-bold">الجلسات</div>
            <ul class="list-group list-group-flush">
                @forelse ($case->sessions as $session)
                    <li class="list-group-item small">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">{{ $session->session_date->format('Y-m-d H:i') }}</span>
                            <span class="text-muted">{{ $session->user?->name }}</span>
                        </div>
                        @if ($session->session_notes)
                            <div class="text-muted mt-1">{{ $session->session_notes }}</div>
                        @endif
                        @if ($session->next_session_date)
                            <div class="text-info mt-1"><i class="bi bi-calendar-event"></i> الجلسة القادمة: {{ $session->next_session_date->format('Y-m-d') }}</div>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-muted small">لا توجد جلسات مسجلة بعد.</li>
                @endforelse
            </ul>
            <div class="card-footer bg-white">
                <form action="{{ route('case-sessions.store') }}" method="POST" class="row g-2">
                    @csrf
                    <input type="hidden" name="cases_id" value="{{ $case->id }}">
                    <div class="col-6"><input type="datetime-local" name="session_date" class="form-control form-control-sm" required></div>
                    <div class="col-6"><input type="date" name="next_session_date" class="form-control form-control-sm" placeholder="الجلسة القادمة"></div>
                    <div class="col-12"><textarea name="session_notes" class="form-control form-control-sm" rows="2" placeholder="ملاحظات الجلسة / المتطلبات والقرار"></textarea></div>
                    <div class="col-12"><button class="btn btn-sm btn-primary w-100">إضافة جلسة</button></div>
                </form>
            </div>
        </div>
    </div>

    {{-- === Right panel: polymorphic document manager === --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-folder2-open"></i> مستندات القضية ({{ $case->attachments->count() }})
            </div>
            <div class="card-body">
                {{-- Drag & drop uploader --}}
                <form id="uploadForm" action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="related_id" value="{{ $case->id }}">
                    <input type="hidden" name="related_type" value="case">
                    <div id="dropZone" class="border border-2 border-dashed rounded-3 text-center p-4 mb-3 text-muted"
                         style="cursor:pointer; transition:.2s;">
                        <i class="bi bi-cloud-arrow-up fs-2"></i>
                        <div>اسحب وأفلت الملف هنا، أو اضغط للاختيار</div>
                        <input type="file" name="file" id="fileInput" class="d-none" required>
                    </div>
                </form>

                {{-- Document grid --}}
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    @forelse ($case->attachments as $doc)
                        <div class="col">
                            <div class="border rounded-3 p-3 text-center h-100 position-relative">
                                <form action="{{ route('attachments.destroy', $doc) }}" method="POST"
                                      class="position-absolute top-0 end-0 m-1"
                                      onsubmit="return confirm('حذف المستند؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-x-circle"></i></button>
                                </form>
                                <i class="bi {{ $fileIcons[$doc->file_type] ?? 'bi-file-earmark' }} fs-1"></i>
                                <div class="small text-truncate mt-2">{{ basename($doc->file_url) }}</div>
                                <div class="text-muted" style="font-size:.7rem;">{{ optional($doc->uploaded_at)->format('Y-m-d') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">لا توجد مستندات مرفوعة لهذه القضية بعد.</div>
                    @endforelse
                </div>
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
