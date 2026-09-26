@extends('cms.parent')
@section('title', 'السوابق القضائية')
@section('page-title', 'مكتبة السوابق القضائية')

@php
    $sourceColors = [
        'محكمة النقض'      => 'danger',
        'محكمة الاستئناف' => 'warning',
        'محكمة عليا'      => 'primary',
        'أخرى'             => 'secondary',
    ];
@endphp

@section('content')

    {{-- ═══════════ Filter Bar ═══════════ --}}
    @include('cms.partials.filter-bar', [
        'action' => route('legal-precedents.index'),
        'filters' => [
            [
                'name' => 'search',
                'type' => 'text',
                'label' => 'بحث',
                'placeholder' => 'العنوان، الملخص...',
                'icon' => 'bi-search',
                'col' => 'col-md-4',
            ],
            [
                'name' => 'source',
                'type' => 'select',
                'label' => 'المصدر',
                'options' => [
                    'محكمة النقض'      => 'محكمة النقض',
                    'محكمة الاستئناف' => 'محكمة الاستئناف',
                    'محكمة عليا'      => 'محكمة عليا',
                    'أخرى'             => 'أخرى',
                ],
                'icon' => 'bi-bank',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'case',
                'type' => 'select',
                'label' => 'القضية المرتبطة',
                'options' => $cases->pluck('case_number', 'id')->toArray(),
                'icon' => 'bi-folder2-open',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'ruling',
                'type' => 'date_range',
                'label' => 'تاريخ الحكم',
                'icon' => 'bi-calendar3',
                'col' => 'col-md-4',
            ],
        ],
    ])

    <div class="row g-3">

        {{-- قائمة السوابق --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-journal-bookmark-fill text-warning me-2"></i>
                            قائمة السوابق
                        </h5>
                        <small class="text-muted">إجمالي: {{ $precedents->total() }} سابقة</small>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">العنوان</th>
                                    <th>المصدر</th>
                                    <th>تاريخ الحكم</th>
                                    <th>القضية المرتبطة</th>
                                    <th class="text-center pe-3">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($precedents as $precedent)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-start">
                                                <i class="bi bi-file-earmark-text-fill text-warning fs-5 me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold">{{ Str::limit($precedent->title, 60) }}</div>
                                                    @if ($precedent->summary)
                                                        <small class="text-muted d-block mt-1">
                                                            {{ Str::limit($precedent->summary, 80) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-{{ $sourceColors[$precedent->source] ?? 'light' }} status-badge">
                                                <i class="bi bi-bank me-1"></i>{{ $precedent->source }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="bi bi-calendar3 text-muted me-1"></i>
                                            {{ optional($precedent->ruling_date)->format('Y-m-d') }}
                                        </td>
                                        <td>
                                            @if ($precedent->case)
                                                <a href="{{ route('cases.show', $precedent->case) }}"
                                                   class="badge text-bg-dark text-decoration-none"
                                                   style="font-family: monospace;">
                                                    {{ $precedent->case->case_number }}
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="btn-group" role="group">
                                                @if ($precedent->external_link)
                                                    <a href="{{ $precedent->external_link }}" target="_blank"
                                                       class="btn btn-sm btn-outline-primary" title="الرابط الخارجي">
                                                        <i class="bi bi-link-45deg"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('legal-precedents.destroy', $precedent) }}" method="POST"
                                                      class="d-inline" onsubmit="return confirm('حذف هذه السابقة؟');">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" title="حذف">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                                            <p class="mb-0">لا توجد سوابق قضائية مضافة.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($precedents->hasPages())
                    <div class="card-footer bg-white border-0">{{ $precedents->links() }}</div>
                @endif
            </div>
        </div>

        {{-- نموذج الإضافة الجانبي --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-plus-circle-fill text-warning me-2"></i>
                        إضافة سابقة قضائية
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('legal-precedents.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-bookmark text-muted me-1"></i> العنوان
                            </label>
                            <input type="text" name="title" class="form-control" maxlength="45" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-bank text-muted me-1"></i> المصدر
                            </label>
                            <select name="source" class="form-select" required>
                                <option value="محكمة النقض">محكمة النقض</option>
                                <option value="محكمة الاستئناف">محكمة الاستئناف</option>
                                <option value="محكمة عليا">محكمة عليا</option>
                                <option value="أخرى">أخرى</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-calendar3 text-muted me-1"></i> تاريخ الحكم
                            </label>
                            <input type="date" name="ruling_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-link text-muted me-1"></i> القضية المرتبطة
                            </label>
                            <select name="cases_id" class="form-select">
                                <option value="">— بدون —</option>
                                @foreach ($cases as $case)
                                    <option value="{{ $case->id }}">
                                        {{ $case->case_number }} — {{ Str::limit($case->case_title, 30) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-globe text-muted me-1"></i> رابط خارجي
                            </label>
                            <input type="url" name="external_link" class="form-control" placeholder="https://...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-file-text text-muted me-1"></i> الملخص
                            </label>
                            <textarea name="summary" class="form-control" rows="3" required></textarea>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="bi bi-check-lg"></i> حفظ السابقة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection