@extends('cms.parent')
@section('title', 'العقود')
@section('page-title', 'إدارة العقود')

@php
    $statusColors = [
        'نشط'    => 'success',
        'منتهي'  => 'secondary',
        'ملغي'   => 'danger',
    ];
    $statusIcons = [
        'نشط'    => 'bi-check-circle-fill',
        'منتهي'  => 'bi-dash-circle',
        'ملغي'   => 'bi-x-circle-fill',
    ];
@endphp

@section('content')

    {{-- ═══════════ Filter Bar ═══════════ --}}
    @include('cms.partials.filter-bar', [
        'action' => route('contracts.index'),
        'filters' => [
            [
                'name' => 'search',
                'type' => 'text',
                'label' => 'بحث',
                'placeholder' => 'نوع العقد، الأطراف...',
                'icon' => 'bi-search',
                'col' => 'col-md-3',
            ],
            [
                'name' => 'status',
                'type' => 'select',
                'label' => 'الحالة',
                'options' => [
                    'نشط'   => 'نشط',
                    'منتهي' => 'منتهي',
                    'ملغي'  => 'ملغي',
                ],
                'icon' => 'bi-flag',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'client',
                'type' => 'select',
                'label' => 'الموكل',
                'options' => $clients->mapWithKeys(fn ($c) => [$c->id => $c->user?->name ?? 'موكل #' . $c->id])->toArray(),
                'icon' => 'bi-person-fill',
                'col' => 'col-md-3',
            ],
            [
                'name' => 'lawyer',
                'type' => 'select',
                'label' => 'المحامي',
                'options' => $lawyers->pluck('name', 'id')->toArray(),
                'icon' => 'bi-briefcase-fill',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'signed',
                'type' => 'date_range',
                'label' => 'تاريخ التوقيع',
                'icon' => 'bi-calendar3',
                'col' => 'col-md-2',
            ],
        ],
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-file-earmark-text-fill text-warning me-2"></i>
                    قائمة العقود
                </h5>
                <small class="text-muted">إجمالي: {{ $contracts->total() }} عقد</small>
            </div>

            <div class="d-flex gap-2">
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('contracts.trashed') }}" class="btn btn-outline-danger">
                        <i class="bi bi-trash2"></i> المحذوفات
                    </a>
                @endif

                @can('create', App\Models\Contract::class)
                    <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> عقد جديد
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">نوع العقد</th>
                            <th>الموكل</th>
                            <th>المحامي</th>
                            <th>القيمة</th>
                            <th>الحالة</th>
                            <th>تاريخ التوقيع</th>
                            <th class="text-center pe-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contracts as $contract)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-semibold">{{ $contract->contract_type }}</span>
                                </td>
                                <td>
                                    @if ($contract->client?->user?->name)
                                        <div class="d-flex align-items-center">
                                            <span class="avatar-initial">
                                                {{ mb_substr($contract->client->user->name, 0, 1) }}
                                            </span>
                                            <span>{{ $contract->client->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($contract->lawyer?->name)
                                        <div class="d-flex align-items-center">
                                            <span class="avatar-initial" style="background:linear-gradient(135deg,#667eea,#764ba2); color:#fff;">
                                                {{ mb_substr($contract->lawyer->name, 0, 1) }}
                                            </span>
                                            <span>{{ $contract->lawyer->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        {{ number_format($contract->contract_value, 2) }}
                                    </span>
                                    <small class="text-muted">₪</small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill text-bg-{{ $statusColors[$contract->contract_status] ?? 'light' }} status-badge">
                                        <i class="bi {{ $statusIcons[$contract->contract_status] ?? 'bi-circle' }} me-1"></i>
                                        {{ $contract->contract_status }}
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    {{ optional($contract->signed_at)->format('Y-m-d') }}
                                </td>
                                <td class="text-center pe-3">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('contracts.show', $contract) }}"
                                           class="btn btn-sm btn-outline-info" title="التفاصيل">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @can('update', $contract)
                                            <a href="{{ route('contracts.edit', $contract) }}"
                                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $contract)
                                            <form action="{{ route('contracts.destroy', $contract) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('حذف هذا العقد؟');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="حذف">
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
                                    <i class="bi bi-file-earmark-x fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-0">لا توجد عقود مسجلة.</p>

                                    @can('create', App\Models\Contract::class)
                                        <a href="{{ route('contracts.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="bi bi-plus-lg"></i> إضافة أول عقد
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($contracts->hasPages())
            <div class="card-footer bg-white border-0">{{ $contracts->links() }}</div>
        @endif
    </div>
@endsection