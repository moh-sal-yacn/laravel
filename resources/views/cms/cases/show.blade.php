@extends('cms.parent')

@section('title', 'ملف القضية '.$case->case_number)
@section('page-title', 'ملف القضية')

@php
    $statusColors = [
        'قيد النظر' => 'warning',
        'مؤجلة'    => 'secondary',
        'منتهية'   => 'success',
        'مؤرشفة'   => 'dark',
    ];
    $statusIcons = [
        'قيد النظر' => 'bi-hourglass-split',
        'مؤجلة'    => 'bi-pause-circle',
        'منتهية'   => 'bi-check-circle-fill',
        'مؤرشفة'   => 'bi-archive-fill',
    ];
    $fileIcons = [
        'pdf'  => 'bi-file-earmark-pdf text-danger',
        'doc'  => 'bi-file-earmark-word text-primary',
        'docx' => 'bi-file-earmark-word text-primary',
        'jpg'  => 'bi-file-earmark-image text-success',
        'jpeg' => 'bi-file-earmark-image text-success',
        'png'  => 'bi-file-earmark-image text-success',
    ];

    // ═══════════ Activity Log: إعداد الأيقونات ═══════════
    $eventIcons = [
        'created' => ['icon' => 'bi-plus-circle-fill',   'color' => 'success'],
        'updated' => ['icon' => 'bi-pencil-fill',        'color' => 'warning'],
        'deleted' => ['icon' => 'bi-trash-fill',         'color' => 'danger'],
    ];
    $eventLabels = [
        'created' => 'إنشاء',
        'updated' => 'تعديل',
        'deleted' => 'حذف',
    ];
    $fieldLabels = [
        'case_number'   => 'رقم القضية',
        'case_title'    => 'عنوان القضية',
        'case_status'   => 'حالة القضية',
        'description'   => 'الوصف',
        'opened_at'     => 'تاريخ الفتح',
        'archived_at'   => 'تاريخ الأرشفة',
        'clients_id'    => 'الموكل',
        'courts_id'     => 'المحكمة',
        'categories_id' => 'التصنيف',
    ];

    $activities = $case->activities()->with('causer')->latest()->limit(20)->get();
@endphp

@section('content')

    {{-- ─── شريط علوي: رقم القضية + الحالة + الأزرار ─── --}}
    <div class="card mb-3 border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <small class="text-white-50 d-block mb-1">رقم القضية</small>
                <h4 class="mb-1 fw-bold">
                    <i class="bi bi-folder2-open text-warning me-2"></i>
                    {{ $case->case_number }}
                </h4>
                <div class="text-white-50 small">{{ $case->case_title }}</div>
            </div>
            <div class="text-end">
                <span class="badge rounded-pill text-bg-{{ $statusColors[$case->case_status] ?? 'light' }} status-badge mb-2">
                    <i class="bi {{ $statusIcons[$case->case_status] ?? 'bi-circle' }} me-1"></i>
                    {{ $case->case_status }}
                </span>
                <div class="d-flex gap-1 flex-wrap justify-content-end">
                    {{-- 🖨️ زر PDF --}}
                    <a href="{{ route('cases.pdf', $case) }}" class="btn btn-sm btn-light" title="تصدير PDF">
                        <i class="bi bi-file-pdf text-danger"></i> PDF
                    </a>

                    {{-- ✏️ زر التعديل --}}
                    @can('update', $case)
                        <a href="{{ route('cases.edit', $case) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> تعديل
                        </a>
                    @endcan

                    {{-- 🔙 زر الرجوع --}}
                    <a href="{{ route('cases.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-arrow-right"></i> رجوع
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- ═══════════ لوحة يسار: بيانات القضية ═══════════ --}}
        <div class="col-lg-5">

            {{-- بيانات القضية --}}
            <div class="card mb-3">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle-fill text-warning me-2"></i>
                        بيانات القضية
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-hash me-1"></i>رقم القضية</span>
                        <span class="fw-semibold" style="font-family: monospace;">{{ $case->case_number }}</span>
                    </div>
                    <div class="p-3 border-bottom d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-bookmark me-1"></i>التصنيف</span>
                        <span class="fw-semibold">{{ $case->category?->category_name ?? '—' }}</span>
                    </div>
                    <div class="p-3 border-bottom d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-person-fill me-1"></i>الموكل</span>
                        @if ($case->client)
                            <a href="{{ route('clients.show', $case->client) }}" class="fw-semibold text-decoration-none">
                                {{ $case->client->user?->name }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                    <div class="p-3 border-bottom d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-bank2 me-1"></i>المحكمة</span>
                        <span class="fw-semibold text-end">
                            {{ $case->court?->court_name ?? '—' }}
                            @if ($case->court?->jurisdiction)
                                <small class="text-muted d-block">{{ $case->court->jurisdiction }}</small>
                            @endif
                        </span>
                    </div>
                    <div class="p-3 border-bottom d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-calendar3 me-1"></i>تاريخ الفتح</span>
                        <span class="fw-semibold">{{ optional($case->opened_at)->format('Y-m-d') }}</span>
                    </div>
                    @if ($case->archived_at)
                        <div class="p-3 border-bottom d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-archive me-1"></i>تاريخ الأرشفة</span>
                            <span class="fw-semibold">{{ $case->archived_at->format('Y-m-d') }}</span>
                        </div>
                    @endif
                    @if ($case->description)
                        <div class="p-3">
                            <div class="text-muted mb-2"><i class="bi bi-file-text me-1"></i>الوصف</div>
                            <p class="small text-muted mb-0">{{ $case->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- أطراف القضية --}}
            <div class="card mb-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people-fill text-warning me-2"></i>
                        أطراف القضية
                    </h5>
                    <span class="badge text-bg-light">{{ $case->participants->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($case->participants as $participant)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="avatar-initial">
                                    {{ mb_substr($participant->name, 0, 1) }}
                                </span>
                                <span class="fw-semibold">{{ $participant->name }}</span>
                            </div>
                            <span class="badge text-bg-info status-badge">{{ $participant->pivot->role_in_case }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small text-center py-3">
                            <i class="bi bi-person-x"></i> لا يوجد أطراف مضافون بعد.
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- الجلسات (Timeline) --}}
            <div class="card">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-calendar-event-fill text-warning me-2"></i>
                        الجلسات
                    </h5>
                    <span class="badge text-bg-light">{{ $case->sessions->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse ($case->sessions as $session)
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge text-bg-primary">
                                    <i class="bi bi-calendar"></i>
                                    {{ $session->session_date->translatedFormat('d M Y - h:i A') }}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> {{ $session->user?->name }}
                                </small>
                            </div>
                            @if ($session->session_notes)
                                <div class="small text-muted mt-2">{{ $session->session_notes }}</div>
                            @endif
                            @if ($session->next_session_date)
                                <div class="small text-info mt-2">
                                    <i class="bi bi-calendar-event"></i>
                                    الجلسة القادمة: {{ $session->next_session_date->translatedFormat('d M Y') }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                            لا توجد جلسات مسجلة بعد.
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-white">
                    <form action="{{ route('case-sessions.store') }}" method="POST" class="row g-2">
                        @csrf
                        <input type="hidden" name="cases_id" value="{{ $case->id }}">
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">تاريخ الجلسة</label>
                            <input type="datetime-local" name="session_date" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">الجلسة القادمة</label>
                            <input type="date" name="next_session_date" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">ملاحظات الجلسة</label>
                            <textarea name="session_notes" class="form-control form-control-sm" rows="2" placeholder="ملاحظات الجلسة / المتطلبات والقرار"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-sm btn-primary w-100">
                                <i class="bi bi-plus-lg"></i> إضافة جلسة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ═══════════ لوحة يمين: المستندات + سجل التعديلات ═══════════ --}}
        <div class="col-lg-7">

            {{-- مستندات القضية --}}
            <div class="card mb-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-folder2-open text-warning me-2"></i>
                        مستندات القضية
                    </h5>
                    <span class="badge text-bg-light">{{ $case->attachments->count() }} ملف</span>
                </div>
                <div class="card-body">

                    {{-- Drag & drop uploader --}}
                    <form id="uploadForm" action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="related_id" value="{{ $case->id }}">
                        <input type="hidden" name="related_type" value="case">
                        <div id="dropZone"
                             class="border border-2 border-dashed rounded-3 text-center p-4 mb-3 text-muted"
                             style="cursor:pointer; transition:.2s; border-color:#f0b429 !important; background:#fffbf0;">
                            <i class="bi bi-cloud-arrow-up-fill fs-1 text-warning"></i>
                            <div class="mt-2 fw-semibold">اسحب وأفلت الملفات هنا</div>
                            <small class="text-muted">أو اضغط للاختيار من جهازك</small>
                            <input type="file" name="file" id="fileInput" class="d-none" required>
                        </div>
                    </form>

                    {{-- Grid المستندات --}}
                    <div class="row row-cols-2 row-cols-md-3 g-3">
                        @forelse ($case->attachments as $doc)
                            <div class="col">
                                <div class="border rounded-3 p-3 text-center h-100 position-relative shadow-sm">
                                    <form action="{{ route('attachments.destroy', $doc) }}" method="POST"
                                          class="position-absolute top-0 end-0 m-1"
                                          onsubmit="return confirm('حذف المستند؟');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-link text-danger p-0" title="حذف">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    </form>
                                    <i class="bi {{ $fileIcons[$doc->file_type] ?? 'bi-file-earmark' }} fs-1"></i>
                                    <div class="small text-truncate mt-2" title="{{ basename($doc->file_url) }}">
                                        {{ basename($doc->file_url) }}
                                    </div>
                                    <div class="text-muted mt-1" style="font-size:.7rem;">
                                        {{ optional($doc->uploaded_at)->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">
                                <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary"></i>
                                <p class="mb-0">لا توجد مستندات مرفوعة لهذه القضية بعد.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ═══════════════ سجل التعديلات (Activity Log) ═══════════════ --}}
            <div class="card">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history text-warning me-2"></i>
                        سجل التعديلات
                    </h5>
                    <span class="badge text-bg-light">{{ $activities->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse ($activities as $activity)
                        @php
                            $event = $eventIcons[$activity->event] ?? ['icon' => 'bi-circle', 'color' => 'secondary'];
                            $label = $eventLabels[$activity->event] ?? $activity->event;
                            $old = $activity->properties['old'] ?? [];
                            $new = $activity->properties['attributes'] ?? [];
                        @endphp
                        <div class="d-flex gap-3 p-3 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:40px;height:40px;background: var(--bs-{{ $event['color'] }}-bg-subtle);">
                                    <i class="bi {{ $event['icon'] }} text-{{ $event['color'] }}"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="badge text-bg-{{ $event['color'] }} me-1">{{ $label }}</span>
                                        <span class="fw-semibold">{{ $activity->description }}</span>
                                    </div>
                                    <small class="text-muted text-nowrap">
                                        <i class="bi bi-clock"></i>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                <div class="small text-muted mb-2">
                                    <i class="bi bi-person-circle"></i>
                                    {{ $activity->causer?->name ?? 'النظام' }}
                                </div>

                                @if ($activity->event === 'updated' && !empty($old))
                                    <div class="bg-light rounded p-2 mt-2">
                                        @foreach ($old as $field => $oldValue)
                                            @php
                                                $newValue = $new[$field] ?? '—';
                                                $fieldLabel = $fieldLabels[$field] ?? $field;

                                                if (in_array($field, ['clients_id', 'courts_id', 'categories_id'])) {
                                                    $modelMap = [
                                                        'clients_id'    => \App\Models\Client::class,
                                                        'courts_id'     => \App\Models\Court::class,
                                                        'categories_id' => \App\Models\Category::class,
                                                    ];
                                                    $nameField = [
                                                        'clients_id'    => null,
                                                        'courts_id'     => 'court_name',
                                                        'categories_id' => 'category_name',
                                                    ];
                                                    if ($modelMap[$field] ?? null) {
                                                        if ($field === 'clients_id') {
                                                            $oldValue = \App\Models\Client::find($oldValue)?->user?->name ?? $oldValue;
                                                            $newValue = \App\Models\Client::find($newValue)?->user?->name ?? $newValue;
                                                        } else {
                                                            $nf = $nameField[$field];
                                                            $oldValue = $modelMap[$field]::find($oldValue)?->{$nf} ?? $oldValue;
                                                            $newValue = $modelMap[$field]::find($newValue)?->{$nf} ?? $newValue;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <div class="small mb-1">
                                                <strong class="text-muted">{{ $fieldLabel }}:</strong>
                                                <span class="badge text-bg-danger-subtle text-danger-emphasis me-1">
                                                    {{ Str::limit($oldValue, 40) }}
                                                </span>
                                                <i class="bi bi-arrow-left text-muted mx-1"></i>
                                                <span class="badge text-bg-success-subtle text-success-emphasis">
                                                    {{ Str::limit($newValue, 40) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($activity->event === 'created' && !empty($new))
                                    <div class="small text-muted">
                                        <i class="bi bi-check-circle"></i>
                                        تم تسجيل القضية بكل بياناتها.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-clock-history fs-1 d-block mb-2 text-secondary"></i>
                            <p class="mb-0">لا توجد تعديلات مسجلة بعد.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadForm = document.getElementById('uploadForm');

    if (dropZone) {
        dropZone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => { if (fileInput.files.length) uploadForm.submit(); });

        ['dragenter', 'dragover'].forEach(evt =>
            dropZone.addEventListener(evt, e => {
                e.preventDefault();
                dropZone.classList.add('border-primary', 'bg-light');
            })
        );
        ['dragleave', 'drop'].forEach(evt =>
            dropZone.addEventListener(evt, e => {
                e.preventDefault();
                dropZone.classList.remove('border-primary', 'bg-light');
            })
        );
        dropZone.addEventListener('drop', e => {
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                uploadForm.submit();
            }
        });
    }
</script>
@endpush