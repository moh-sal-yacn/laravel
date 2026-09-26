@extends('cms.parent')

@section('title', 'القضايا')
@section('page-title', 'إدارة القضايا')

@php
    $statusColors = [
        'قيد النظر' => 'warning',
        'مؤجلة'    => 'secondary',
        'منتهية'   => 'success',
        'مؤرشفة'   => 'dark',
    ];
@endphp

@section('content')

    {{-- ═══════════ Filter Bar ═══════════ --}}
    @include('cms.partials.filter-bar', [
        'action' => route('cases.index'),
        'filters' => [
            [
                'name' => 'search',
                'type' => 'text',
                'label' => 'بحث',
                'placeholder' => 'رقم القضية، العنوان...',
                'icon' => 'bi-search',
                'col' => 'col-md-3',
            ],
            [
                'name' => 'status',
                'type' => 'select',
                'label' => 'الحالة',
                'options' => [
                    'قيد النظر' => 'قيد النظر',
                    'مؤجلة'    => 'مؤجلة',
                    'منتهية'   => 'منتهية',
                    'مؤرشفة'   => 'مؤرشفة',
                ],
                'icon' => 'bi-flag',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'court',
                'type' => 'select',
                'label' => 'المحكمة',
                'options' => $courts->pluck('court_name', 'id')->toArray(),
                'icon' => 'bi-bank2',
                'col' => 'col-md-3',
            ],
            [
                'name' => 'category',
                'type' => 'select',
                'label' => 'التصنيف',
                'options' => $categories->pluck('category_name', 'id')->toArray(),
                'icon' => 'bi-bookmark',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'opened',
                'type' => 'date_range',
                'label' => 'تاريخ الفتح',
                'icon' => 'bi-calendar3',
                'col' => 'col-md-2',
            ],
        ],
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-folder2-open text-warning me-2"></i>
                    قائمة القضايا
                </h5>
                <small class="text-muted">إجمالي: {{ $cases->total() }} قضية</small>
            </div>

            <div class="d-flex gap-2">
                {{-- 🗑️ زر "المحذوفات" (للمدير فقط) --}}
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('cases.trashed') }}" class="btn btn-outline-danger">
                        <i class="bi bi-trash2"></i> المحذوفات
                    </a>
                @endif

                {{-- ✅ زر "قضية جديدة" --}}
                @can('create', App\Models\CourtCase::class)
                    <a href="{{ route('cases.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> قضية جديدة
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">رقم القضية</th>
                            <th>عنوان القضية</th>
                            <th>الموكل</th>
                            <th>المحكمة</th>
                            <th>الحالة</th>
                            <th>تاريخ الفتح</th>
                            <th class="text-center pe-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cases as $case)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge text-bg-dark fw-normal" style="font-family: monospace;">
                                        {{ $case->case_number }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ Str::limit($case->case_title, 55) }}</div>
                                </td>
                                <td>
                                    @if ($case->client?->user?->name)
                                        <div class="d-flex align-items-center">
                                            <span class="avatar-initial">
                                                {{ mb_substr($case->client->user->name, 0, 1) }}
                                            </span>
                                            <span>{{ $case->client->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="bi bi-bank2 text-muted me-1"></i>
                                    {{ $case->court?->court_name ?? '—' }}
                                </td>
                                <td>
                                    <span class="badge rounded-pill text-bg-{{ $statusColors[$case->case_status] ?? 'light' }} status-badge">
                                        {{ $case->case_status }}
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    {{ optional($case->opened_at)->format('Y-m-d') }}
                                </td>
                                <td class="text-center pe-3">
                                    <div class="btn-group" role="group">

                                        {{-- 👁️ التفاصيل: للجميع --}}
                                        <a href="{{ route('cases.show', $case) }}"
                                           class="btn btn-sm btn-outline-info" title="التفاصيل">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- ✏️ التعديل --}}
                                        @can('update', $case)
                                            <a href="{{ route('cases.edit', $case) }}"
                                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan

                                        {{-- 🗑️ الحذف --}}
                                        @can('delete', $case)
                                            <form action="{{ route('cases.destroy', $case) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه القضية؟');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-0">لا توجد قضايا مسجلة حتى الآن.</p>

                                    @can('create', App\Models\CourtCase::class)
                                        <a href="{{ route('cases.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="bi bi-plus-lg"></i> إضافة أول قضية
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($cases->hasPages())
            <div class="card-footer bg-white border-0">{{ $cases->links() }}</div>
        @endif
    </div>
@endsection