@extends('cms.parent')
@section('title', 'الموكلون')
@section('page-title', 'إدارة الموكلين')

@php
    $kindColors = [
        'individual' => ['label' => 'فرد',   'color' => 'info',   'icon' => 'bi-person'],
        'company'    => ['label' => 'شركة',  'color' => 'primary','icon' => 'bi-building'],
    ];
@endphp

@section('content')

    {{-- ═══════════ Filter Bar ═══════════ --}}
    @include('cms.partials.filter-bar', [
        'action' => route('clients.index'),
        'filters' => [
            [
                'name' => 'search',
                'type' => 'text',
                'label' => 'بحث',
                'placeholder' => 'الاسم، الإيميل، الرقم الوطني...',
                'icon' => 'bi-search',
                'col' => 'col-md-4',
            ],
            [
                'name' => 'kind',
                'type' => 'select',
                'label' => 'النوع',
                'options' => [
                    'individual' => 'فرد',
                    'company'    => 'شركة',
                ],
                'icon' => 'bi-person-badge',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'national_id',
                'type' => 'text',
                'label' => 'الرقم الوطني',
                'placeholder' => '1234567890',
                'icon' => 'bi-card-text',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'registered',
                'type' => 'date_range',
                'label' => 'تاريخ التسجيل',
                'icon' => 'bi-calendar3',
                'col' => 'col-md-4',
            ],
        ],
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-people-fill text-warning me-2"></i>
                    قائمة الموكلين
                </h5>
                <small class="text-muted">إجمالي: {{ $clients->total() }} موكل</small>
            </div>

            <div class="d-flex gap-2">
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('clients.trashed') }}" class="btn btn-outline-danger">
                        <i class="bi bi-trash2"></i> المحذوفات
                    </a>
                @endif

                @can('create', App\Models\Client::class)
                    @if (Route::has('clients.create'))
                        <a href="{{ route('clients.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> موكل جديد
                        </a>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>النوع</th>
                            <th>الرقم الوطني</th>
                            <th class="text-center pe-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $client)
                            @php
                                $kind = $kindColors[$client->client_kind] ?? ['label' => $client->client_kind, 'color' => 'light', 'icon' => 'bi-question'];
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    @if ($client->user?->name)
                                        <div class="d-flex align-items-center">
                                            <span class="avatar-initial">
                                                {{ mb_substr($client->user->name, 0, 1) }}
                                            </span>
                                            <div>
                                                <div class="fw-semibold">{{ $client->user->name }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($client->user?->email)
                                        <i class="bi bi-envelope text-muted me-1"></i>
                                        <span dir="ltr">{{ $client->user->email }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $kind['color'] }} status-badge">
                                        <i class="bi {{ $kind['icon'] }} me-1"></i>{{ $kind['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if ($client->national_id)
                                        <span style="font-family: monospace;">{{ $client->national_id }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center pe-3">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('clients.show', $client) }}"
                                           class="btn btn-sm btn-outline-info" title="التفاصيل">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @can('update', $client)
                                            <a href="{{ route('clients.edit', $client) }}"
                                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $client)
                                            <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل تريد حذف هذا الموكل؟');">
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
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-0">لا يوجد موكلون مسجلون.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($clients->hasPages())
            <div class="card-footer bg-white border-0">{{ $clients->links() }}</div>
        @endif
    </div>
@endsection