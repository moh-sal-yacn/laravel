@extends('cms.parent')
@section('title', 'المستخدمون')
@section('page-title', 'إدارة المستخدمين')

@php
    $typeLabels = [
        'admin'  => ['label' => 'مدير',    'color' => 'danger',   'icon' => 'bi-shield-fill-check'],
        'lawyer' => ['label' => 'محامي',   'color' => 'primary',  'icon' => 'bi-briefcase-fill'],
        'client' => ['label' => 'موكل',    'color' => 'info',     'icon' => 'bi-person-fill'],
        'staff'  => ['label' => 'موظف',    'color' => 'secondary','icon' => 'bi-person-badge-fill'],
    ];
@endphp

@section('content')

    {{-- ═══════════ Filter Bar ═══════════ --}}
    @include('cms.partials.filter-bar', [
        'action' => route('users.index'),
        'filters' => [
            [
                'name' => 'search',
                'type' => 'text',
                'label' => 'بحث',
                'placeholder' => 'الاسم، الإيميل، الجوال...',
                'icon' => 'bi-search',
                'col' => 'col-md-3',
            ],
            [
                'name' => 'type',
                'type' => 'select',
                'label' => 'النوع',
                'options' => [
                    'admin'  => 'مدير',
                    'lawyer' => 'محامي',
                    'client' => 'موكل',
                    'staff'  => 'موظف',
                ],
                'icon' => 'bi-person-badge',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'role',
                'type' => 'select',
                'label' => 'الدور',
                'options' => $roles->pluck('role_name', 'id')->toArray(),
                'icon' => 'bi-shield-lock',
                'col' => 'col-md-3',
            ],
            [
                'name' => 'status',
                'type' => 'select',
                'label' => 'الحالة',
                'options' => [
                    'active'   => 'مفعّل',
                    'inactive' => 'موقوف',
                ],
                'icon' => 'bi-toggle-on',
                'col' => 'col-md-2',
            ],
            [
                'name' => 'registered',
                'type' => 'date_range',
                'label' => 'تاريخ التسجيل',
                'icon' => 'bi-calendar3',
                'col' => 'col-md-2',
            ],
        ],
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-person-badge-fill text-warning me-2"></i>
                    قائمة المستخدمين
                </h5>
                <small class="text-muted">إجمالي: {{ $users->total() }} مستخدم</small>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> مستخدم جديد
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>النوع</th>
                            <th>الأدوار</th>
                            <th>الحالة</th>
                            <th class="text-center pe-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                $type = $typeLabels[$user->user_type] ?? ['label' => $user->user_type, 'color' => 'light', 'icon' => 'bi-person'];
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar-initial">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </span>
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="bi bi-envelope text-muted me-1"></i>
                                    <span dir="ltr">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $type['color'] }} status-badge">
                                        <i class="bi {{ $type['icon'] }} me-1"></i>{{ $type['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @forelse ($user->roles as $role)
                                        <span class="badge text-bg-light border">{{ $role->role_name }}</span>
                                    @empty
                                        <span class="text-muted small">—</span>
                                    @endforelse
                                </td>
                                <td>
                                    @if ($user->is_active)
                                        <span class="badge text-bg-success status-badge">
                                            <i class="bi bi-check-circle-fill me-1"></i>مفعّل
                                        </span>
                                    @else
                                        <span class="badge text-bg-danger status-badge">
                                            <i class="bi bi-x-circle-fill me-1"></i>موقوف
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-3">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.show', $user) }}"
                                           class="btn btn-sm btn-outline-info" title="التفاصيل">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="btn btn-sm btn-outline-warning" title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل تريد حذف هذا المستخدم؟');">
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
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-0">لا يوجد مستخدمون.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="card-footer bg-white border-0">{{ $users->links() }}</div>
        @endif
    </div>
@endsection