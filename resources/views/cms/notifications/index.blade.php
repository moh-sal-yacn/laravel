@extends('cms.parent')

@section('title', 'الإشعارات')
@section('page-title', 'الإشعارات')

@php
    $eventIcons = [
        'case_created'         => ['icon' => 'bi-folder-plus',        'color' => 'success'],
        'case_updated'         => ['icon' => 'bi-pencil-square',      'color' => 'info'],
        'appointment_reminder' => ['icon' => 'bi-calendar-check',     'color' => 'primary'],
        'session_reminder'     => ['icon' => 'bi-calendar-event',     'color' => 'warning'],
        'contract_signed'      => ['icon' => 'bi-file-earmark-check', 'color' => 'success'],
    ];
@endphp

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">

        {{-- رأس الصفحة --}}
        <div class="card mb-3">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bell-fill text-warning me-2"></i>
                        الإشعارات
                    </h5>
                    <small class="text-muted">
                        إجمالي: {{ $notifications->total() }} إشعار
                    </small>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('notifications.unread') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-envelope"></i> غير المقروءة
                    </a>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-list-ul"></i> الكل
                    </a>

                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-success">
                                <i class="bi bi-check-all"></i> تعليم الكل كمقروء
                            </button>
                        </form>
                    @endif

                    @if (auth()->user()->readNotifications->count() > 0)
                        <form action="{{ route('notifications.clearRead') }}" method="POST" class="d-inline"
                              onsubmit="return confirm('حذف كل الإشعارات المقروءة؟');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> حذف المقروءة
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card-body p-0">
                @forelse ($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $type = $data['type'] ?? 'default';
                        $style = $eventIcons[$type] ?? ['icon' => 'bi-bell', 'color' => 'secondary'];
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <div class="p-3 border-bottom d-flex gap-3 {{ $isUnread ? 'bg-light' : '' }}">
                        {{-- أيقونة --}}
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;background: var(--bs-{{ $style['color'] }}-bg-subtle);">
                                <i class="bi {{ $data['icon'] ?? $style['icon'] }} text-{{ $data['color'] ?? $style['color'] }} fs-4"></i>
                            </div>
                        </div>

                        {{-- التفاصيل --}}
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    @if ($isUnread)
                                        <span class="badge text-bg-danger me-1">جديد</span>
                                    @endif
                                    <span class="fw-semibold">{{ $data['title'] ?? 'إشعار' }}</span>
                                </div>
                                <small class="text-muted text-nowrap">
                                    <i class="bi bi-clock"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <div class="text-muted small mb-2">
                                {{ $data['message'] ?? '' }}
                            </div>

                            {{-- الأزرار --}}
                            <div class="d-flex gap-2">
                                @if (isset($data['url']))
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-box-arrow-up-right"></i> عرض
                                        </button>
                                    </form>
                                @endif

                                @if ($isUnread)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-check-lg"></i> تعليم كمقروء
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('حذف هذا الإشعار؟');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary"></i>
                        <p class="mb-0">لا توجد إشعارات.</p>
                    </div>
                @endforelse
            </div>

            @if ($notifications->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

@endsection