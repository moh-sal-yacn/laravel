<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'نظام إدارة مكتب المحاماة')</title>

    {{--
        AdminLTE 4 (official) — Bootstrap 5.3 is bundled inside adminlte.rtl.min.css,
        so no separate Bootstrap CSS link is needed. Pinned versions match the
        official "Getting Started" guide at adminlte.io/themes/v4/docs.
    --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.9.1/dist/css/adminlte.rtl.min.css">

    <style>
        .status-badge { font-size: .78rem; padding: .35em .7em; }
        .sidebar-brand .brand-text { margin-inline-start: .5rem; }
    </style>

    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    {{-- Header --}}
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('dashboard') }}" class="nav-link fw-bold">نظام إدارة مكتب المحاماة</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link text-muted small">{{ now()->translatedFormat('l، d F Y') }}</span>
                </li>
            </ul>
        </div>
    </nav>

    {{-- Sidebar --}}
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
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

                    <li class="nav-header">الإدارة المالية</li>
                    <li class="nav-item">
                        <a href="{{ route('financial-records.index') }}" class="nav-link {{ request()->routeIs('financial-records.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-cash-coin"></i><p>السجلات المالية</p>
                        </a>
                    </li>

                    <li class="nav-header">المحتوى</li>
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
                    <li class="nav-item">
                        <a href="{{ route('contact-channels.index') }}" class="nav-link {{ request()->routeIs('contact-channels.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-chat-dots"></i><p>رسائل التواصل</p>
                        </a>
                    </li>

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
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <h3 class="mb-0">@yield('page-title', 'لوحة التحكم')</h3>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
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
            <span>نظام إدارة مكتب المحاماة</span>
            <span>&copy; {{ now()->year }}</span>
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
