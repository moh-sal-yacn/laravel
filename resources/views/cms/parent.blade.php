<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'نظام إدارة مكتب المحاماة')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.9.1/dist/css/adminlte.rtl.min.css">

    {{-- خط عربي احترافي --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ───── الخط والأساسيات ───── */
        body, .navbar, .sidebar-menu, .btn, .form-control, .table {
            font-family: 'Cairo', system-ui, -apple-system, sans-serif !important;
        }

        /* ───── خلفية عامة أنيقة ───── */
        body.bg-body-tertiary {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%) !important;
            min-height: 100vh;
        }

        /* ───── الـ sidebar ───── */
        .app-sidebar {
            background: linear-gradient(180deg, #1a2a3a 0%, #0f1c2a 100%) !important;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar-brand {
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 0;
        }

        .sidebar-brand .brand-link {
            color: #fff !important;
            font-weight: 700;
        }

        .sidebar-brand .brand-link i {
            color: #f0b429;
            font-size: 1.6rem;
        }

        .sidebar-menu .nav-link {
            color: rgba(255, 255, 255, 0.75) !important;
            border-radius: 8px;
            margin: 2px 8px;
            padding: 10px 14px;
            transition: all 0.2s ease;
        }

        .sidebar-menu .nav-link:hover {
            background: rgba(240, 180, 41, 0.12) !important;
            color: #f0b429 !important;
            transform: translateX(-3px);
        }

        .sidebar-menu .nav-link.active {
            background: linear-gradient(90deg, #f0b429 0%, #d99e1f 100%) !important;
            color: #1a2a3a !important;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(240, 180, 41, 0.4);
        }

        .sidebar-menu .nav-header {
            color: #f0b429 !important;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 14px 20px 6px;
            opacity: 0.85;
        }

        /* ───── الـ Header ───── */
        .app-header {
            background: #fff !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border-bottom: 3px solid #f0b429;
        }

        .app-header .navbar-brand {
            color: #1a2a3a !important;
            font-weight: 700;
        }

        /* ───── الكروت ───── */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: all 0.25s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.10);
        }

        /* ───── بطاقات الإحصائيات ───── */
        .stat-card {
            position: relative;
            overflow: hidden;
            color: #fff;
            border-radius: 14px;
            padding: 1.25rem;
            min-height: 120px;
        }

        .stat-card .stat-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3.5rem;
            opacity: 0.25;
        }

        .stat-card .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-card .stat-change {
            font-size: 0.78rem;
            margin-top: 0.6rem;
            opacity: 0.9;
        }

        .stat-primary   { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-success   { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .stat-warning   { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-info      { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        /* ───── الجداول ───── */
        .table thead th {
            background: #f8f9fb;
            color: #495057;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e9ecef;
        }

        .table-hover tbody tr {
            transition: background 0.15s ease;
        }

        .table-hover tbody tr:hover {
            background: #fef9e7 !important;
        }

        .status-badge {
            font-size: 0.78rem;
            padding: 0.4em 0.85em;
            font-weight: 600;
        }

        /* ───── الأزرار ───── */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        .btn-primary {
            background: linear-gradient(135deg, #f0b429 0%, #d99e1f 100%);
            border: none;
            color: #1a2a3a;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #d99e1f 0%, #b8860b 100%);
            color: #1a2a3a;
        }

        /* ───── Avatar ───── */
        .avatar-initial {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f0b429, #d99e1f);
            color: #1a2a3a;
            font-weight: 700;
            font-size: 0.85rem;
            margin-inline-end: 6px;
        }

        /* ───── صفحة العنوان ───── */
        .app-content-header {
            background: transparent;
            padding: 1rem 0;
        }

        .app-content-header h3 {
            color: #1a2a3a;
            font-weight: 700;
            font-size: 1.5rem;
            border-inline-end: 4px solid #f0b429;
            padding-inline-end: 12px;
        }

        /* ───── الـ Footer ───── */
        .app-footer {
            background: #fff;
            border-top: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
        }

        /* ───── التنبيهات ───── */
        .alert {
            border-radius: 10px;
        }

        .alert i.fs-5 {
            flex-shrink: 0;
        }

        /* ───── قائمة المستخدم ───── */
        .user-menu .nav-link {
            padding: .4rem .75rem;
            border-radius: 10px;
            transition: all .2s ease;
        }

        .user-menu .nav-link:hover {
            background: #f8f9fb;
        }

        .user-menu .dropdown-menu {
            border-radius: 12px;
            padding: .5rem 0;
            min-width: 240px;
        }

        .user-menu .dropdown-item {
            padding: .55rem 1rem;
            font-size: .9rem;
            transition: all .15s ease;
        }

        .user-menu .dropdown-item:hover {
            background: #fef9e7;
        }

        .user-menu .dropdown-item.text-danger:hover {
            background: #fff5f5;
        }

        /* ───── قائمة الإشعارات ───── */
        .notification-menu .dropdown-menu {
            border-radius: 12px;
            padding: 0;
            min-width: 340px;
            max-height: 450px;
            overflow-y: auto;
        }

        .notification-menu .dropdown-item {
            padding: .65rem 1rem;
            border-bottom: 1px solid #f1f3f5;
            transition: all .15s ease;
            white-space: normal;
        }

        .notification-menu .dropdown-item:hover {
            background: #fef9e7;
        }

        .notification-menu .dropdown-item:last-child {
            border-bottom: none;
        }

        .notification-menu .unread {
            background: #f8f9fb;
            border-inline-start: 3px solid #f0b429;
        }
    </style>

    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    {{-- ─────────── Header ─────────── --}}
    <nav class="app-header navbar navbar-expand">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list fs-4"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('dashboard') }}" class="nav-link fw-bold">
                        <i class="bi bi-briefcase-fill text-warning me-1"></i>
                        نظام إدارة مكتب المحاماة
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">

                {{-- التاريخ --}}
                <li class="nav-item d-none d-md-flex align-items-center">
                    <span class="nav-link text-muted small">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ now()->translatedFormat('l، d F Y') }}
                    </span>
                </li>

                {{-- ═══════════ 🔔 الإشعارات ═══════════ --}}
                @auth
                <li class="nav-item dropdown notification-menu">
                    <a class="nav-link position-relative" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill fs-5"></i>
                        @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                        @endphp
                        @if ($unreadCount > 0)
                            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"
                                  style="font-size:.65rem;">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                        <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center bg-light">
                            <strong class="small">
                                <i class="bi bi-bell-fill text-warning me-1"></i>
                                الإشعارات
                            </strong>
                            @if ($unreadCount > 0)
                                <span class="badge text-bg-danger">{{ $unreadCount }} جديد</span>
                            @else
                                <span class="badge text-bg-secondary">0</span>
                            @endif
                        </li>

                        @forelse (auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                            @php
                                $data = $notification->data;
                                $isUnread = is_null($notification->read_at);
                            @endphp
                            <li>
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}"
                                      method="POST" class="m-0">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="dropdown-item {{ $isUnread ? 'unread' : '' }}">
                                        <div class="d-flex">
                                            <div class="me-2">
                                                <i class="bi {{ $data['icon'] ?? 'bi-bell' }} text-{{ $data['color'] ?? 'secondary' }} fs-5"></i>
                                            </div>
                                            <div class="flex-grow-1 text-end">
                                                <div class="fw-semibold small">{{ $data['title'] ?? 'إشعار' }}</div>
                                                <div class="text-muted" style="font-size:.75rem;">
                                                    {{ Str::limit($data['message'] ?? '', 70) }}
                                                </div>
                                                <div class="text-muted mt-1" style="font-size:.7rem;">
                                                    <i class="bi bi-clock"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="px-3 py-4 text-center text-muted small">
                                <i class="bi bi-bell-slash d-block mb-2 fs-3"></i>
                                لا توجد إشعارات
                            </li>
                        @endforelse

                        <li>
                            <a class="dropdown-item text-center text-primary fw-semibold py-2 bg-light"
                               href="{{ route('notifications.index') }}">
                                عرض كل الإشعارات
                                <i class="bi bi-arrow-left ms-1"></i>
                            </a>
                        </li>
                    </ul>
                </li>
                @endauth

                {{-- ═══════════ قائمة المستخدم ═══════════ --}}
                @auth
                <li class="nav-item dropdown user-menu ms-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">

                        <span class="avatar-initial">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </span>

                        <div class="d-none d-md-block text-end">
                            <div class="fw-semibold lh-1 small">{{ auth()->user()->name }}</div>
                            <div class="text-muted lh-1" style="font-size: .7rem;">
                                {{ auth()->user()->roles->first()?->role_name ?? 'مستخدم' }}
                            </div>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-semibold">{{ auth()->user()->name }}</div>
                            <small class="text-muted d-block" dir="ltr" style="font-size:.72rem;">
                                {{ auth()->user()->email }}
                            </small>
                            @if (auth()->user()->roles->first())
                                <span class="badge text-bg-warning status-badge mt-1">
                                    <i class="bi bi-shield-fill me-1"></i>
                                    {{ auth()->user()->roles->first()->role_name }}
                                </span>
                            @endif
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle me-2 text-primary"></i>
                                الملف الشخصي
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2 text-info"></i>
                                لوحة التحكم
                            </a>
                        </li>

                        <li><hr class="dropdown-divider my-1"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endauth
            </ul>
        </div>
    </nav>

    {{-- ─────────── Sidebar ─────────── --}}
    <aside class="app-sidebar shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <i class="bi bi-briefcase-fill"></i>
                <span class="brand-text fw-light">مكتب المحاماة</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" role="menu">

                    <li class="nav-header">الملفات القانونية</li>
                    <li class="nav-item">
                        <a href="{{ route('cases.index') }}" class="nav-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-folder2-open"></i><p>القضايا</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contracts.index') }}" class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-file-earmark-text"></i><p>العقود</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('courts.index') }}" class="nav-link {{ request()->routeIs('courts.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bank"></i><p>المحاكم</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tags"></i><p>التصنيفات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('legal-precedents.index') }}" class="nav-link {{ request()->routeIs('legal-precedents.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-bookmark"></i><p>السوابق القضائية</p>
                        </a>
                    </li>

                    <li class="nav-header">العملاء والمواعيد</li>
                    <li class="nav-item">
                        <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i><p>الموكلون</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-calendar-plus"></i><p>طلبات الحجز</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-calendar-check"></i><p>المواعيد</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('service-ratings.index') }}" class="nav-link {{ request()->routeIs('service-ratings.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-star"></i><p>تقييمات الخدمة</p>
                        </a>
                    </li>

                    @if (auth()->user()->hasAnyRole(['مدير النظام', 'موظف إداري']))
                        <li class="nav-header">الإدارة المالية</li>
                        <li class="nav-item">
                            <a href="{{ route('financial-records.index') }}" class="nav-link {{ request()->routeIs('financial-records.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-cash-coin"></i><p>السجلات المالية</p>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasAnyRole(['مدير النظام', 'محامي', 'موظف إداري']))
                        <li class="nav-header">المحتوى</li>

                        @if (auth()->user()->hasAnyRole(['مدير النظام', 'محامي']))
                            <li class="nav-item">
                                <a href="{{ route('articles.index') }}" class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-newspaper"></i><p>المقالات</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('featured-companies.index') }}" class="nav-link {{ request()->routeIs('featured-companies.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-building"></i><p>الشركات المميزة</p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('contact-channels.index') }}" class="nav-link {{ request()->routeIs('contact-channels.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-chat-dots"></i><p>رسائل التواصل</p>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->isAdmin())
                        <li class="nav-header">النظام</li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person-badge"></i><p>المستخدمون</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-shield-lock"></i><p>الأدوار</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-key"></i><p>الصلاحيات</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    {{-- ─────────── Main content ─────────── --}}
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <h3 class="mb-0">@yield('page-title', 'لوحة التحكم')</h3>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                        <span class="flex-grow-1">{{ session('success') }}</span>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                        <span class="flex-grow-1">{{ session('error') }}</span>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>
                        <span class="flex-grow-1">{{ session('warning') }}</span>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                        <span class="flex-grow-1">{{ session('info') }}</span>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                            <strong class="flex-grow-1">يوجد أخطاء في المدخلات:</strong>
                            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                        </div>
                        <ul class="mb-0 ps-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <div class="container-fluid d-flex justify-content-between small text-muted">
            <span><i class="bi bi-briefcase-fill text-warning me-1"></i> نظام إدارة مكتب المحاماة</span>
            <span>&copy; {{ now()->year }} — جميع الحقوق محفوظة</span>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.9.1/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>