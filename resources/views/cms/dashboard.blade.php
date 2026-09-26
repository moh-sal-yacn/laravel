@extends('cms.parent')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم الرئيسية')

@php
    use App\Models\CourtCase;
    use App\Models\Contract;
    use App\Models\Client;
    use App\Models\Booking;
    use App\Models\CaseSession;
    use App\Models\Appointment;
    use App\Models\User;

    $user = auth()->user();
    $isAdmin   = $user->isAdmin();
    $isLawyer  = $user->isLawyer();
    $isClient  = $user->isClient();
    $isStaff   = $user->hasRole('موظف إداري');

    // ═══════════════ إعداد الاستعلامات حسب الدور ═══════════════

    // ─── القضايا ───
    if ($isAdmin || $isStaff) {
        $casesQuery = CourtCase::query();
    } elseif ($isLawyer) {
        $casesQuery = CourtCase::whereHas('participants', fn ($q) => $q->where('users_id', $user->id));
    } elseif ($isClient) {
        $casesQuery = CourtCase::whereHas('client', fn ($q) => $q->where('users_id', $user->id));
    } else {
        $casesQuery = CourtCase::whereRaw('1 = 0'); // لا شيء
    }

    $totalCases  = (clone $casesQuery)->count();
    $activeCases = (clone $casesQuery)->where('case_status', 'قيد النظر')->count();
    $closedCases = (clone $casesQuery)->where('case_status', 'منتهية')->count();

    // ─── الموكلون ───
    if ($isAdmin || $isStaff) {
        $totalClients = Client::count();
    } elseif ($isLawyer) {
        $totalClients = Client::where(function ($q) use ($user) {
            $q->whereHas('cases.participants', fn ($sub) => $sub->where('users_id', $user->id))
              ->orWhereHas('contracts', fn ($sub) => $sub->where('users_id', $user->id));
        })->count();
    } elseif ($isClient) {
        $totalClients = 1;
    } else {
        $totalClients = 0;
    }

    // ─── العقود ───
    if ($isAdmin || $isStaff) {
        $contractsQuery = Contract::query();
    } elseif ($isLawyer) {
        $contractsQuery = Contract::where('users_id', $user->id);
    } elseif ($isClient) {
        $contractsQuery = Contract::whereHas('client', fn ($q) => $q->where('users_id', $user->id));
    } else {
        $contractsQuery = Contract::whereRaw('1 = 0');
    }
    $totalContracts = (clone $contractsQuery)->count();

    // ─── الجلسات ───
    if ($isAdmin || $isStaff) {
        $sessionsQuery = CaseSession::query();
    } elseif ($isLawyer) {
        $sessionsQuery = CaseSession::where('users_id', $user->id);
    } elseif ($isClient) {
        $sessionsQuery = CaseSession::whereHas('case.client', fn ($q) => $q->where('users_id', $user->id));
    } else {
        $sessionsQuery = CaseSession::whereRaw('1 = 0');
    }
    $totalSessions = (clone $sessionsQuery)->count();

    // ─── الجلسات القادمة ───
    $upcomingSessions = (clone $sessionsQuery)
        ->where('session_date', '>=', now())
        ->with(['case', 'user'])
        ->orderBy('session_date')
        ->limit(5)
        ->get();

    // ─── الحجوزات المعلقة ───
    $pendingBookings = Booking::where('status', 'قيد الانتظار')->count();

    // ─── المواعيد ───
    if ($isAdmin || $isStaff) {
        $appointmentsQuery = Appointment::query();
    } elseif ($isLawyer) {
        $appointmentsQuery = Appointment::where('users_id', $user->id);
    } elseif ($isClient) {
        $appointmentsQuery = Appointment::where('clients_id', $user->client?->id);
    } else {
        $appointmentsQuery = Appointment::whereRaw('1 = 0');
    }
    $totalAppointments = (clone $appointmentsQuery)->count();
    $todayAppointments = (clone $appointmentsQuery)
        ->whereDate('appointment_date', today())
        ->where('status', 'مجدول')
        ->count();

    // ─── المراسلات ───
    $newMessages = class_exists(\App\Models\ContactChannel::class)
        ? \App\Models\ContactChannel::where('status', 'جديد')->count()
        : 0;

    // ─── المحامون (للمدير فقط) ───
    $totalLawyers = $isAdmin ? User::where('user_type', 'lawyer')->count() : 0;
@endphp

@section('content')

    {{-- ═══════ ترحيب شخصي ═══════ --}}
    <div class="card mb-4 border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
                <span class="avatar-initial me-3" style="width:56px;height:56px;font-size:1.5rem;">
                    {{ mb_substr($user->name, 0, 1) }}
                </span>
                <div>
                    <h5 class="mb-1 fw-bold">مرحباً، {{ $user->name }} 👋</h5>
                    <small class="text-white-50">
                        <i class="bi bi-shield-fill-check me-1"></i>
                        {{ $user->roles->first()?->role_name ?? 'مستخدم' }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ now()->translatedFormat('l، d F Y') }}
                    </small>
                </div>
            </div>
            <div class="text-end d-none d-md-block">
                <div class="text-white-50 small mb-1">آخر دخول</div>
                <div class="fw-semibold">{{ $user->updated_at?->diffForHumans() ?? 'الآن' }}</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--              بطاقات الإحصائيات (حسب الدور)              --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- 📁 القضايا --}}
        <div class="col-md-6 col-lg-3">
            <div class="stat-card stat-primary">
                <i class="bi bi-folder2-open stat-icon"></i>
                <div class="stat-label">
                    @if ($isLawyer) قضاياي
                    @elseif ($isClient) قضاياي
                    @else القضايا
                    @endif
                </div>
                <div class="stat-value">{{ $totalCases }}</div>
                <div class="stat-change">
                    <i class="bi bi-hourglass-split"></i> {{ $activeCases }} قيد النظر
                </div>
            </div>
        </div>

        {{-- 👥 الموكلون / المواعيد --}}
        @if ($isAdmin || $isStaff)
            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-success">
                    <i class="bi bi-people-fill stat-icon"></i>
                    <div class="stat-label">الموكلون</div>
                    <div class="stat-value">{{ $totalClients }}</div>
                    <div class="stat-change">
                        @if ($isAdmin)
                            <i class="bi bi-person-badge"></i> {{ $totalLawyers }} محامٍ مسجّل
                        @else
                            <i class="bi bi-check-circle"></i> إجمالي الموكلين
                        @endif
                    </div>
                </div>
            </div>
        @elseif ($isLawyer)
            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-success">
                    <i class="bi bi-people-fill stat-icon"></i>
                    <div class="stat-label">موكليّ</div>
                    <div class="stat-value">{{ $totalClients }}</div>
                    <div class="stat-change">
                        <i class="bi bi-person-check"></i> مرتبطون بك
                    </div>
                </div>
            </div>
        @elseif ($isClient)
            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-success">
                    <i class="bi bi-calendar-check stat-icon"></i>
                    <div class="stat-label">مواعيدي</div>
                    <div class="stat-value">{{ $totalAppointments }}</div>
                    <div class="stat-change">
                        <i class="bi bi-clock-history"></i> {{ $todayAppointments }} اليوم
                    </div>
                </div>
            </div>
        @endif

        {{-- 📄 العقود --}}
        <div class="col-md-6 col-lg-3">
            <div class="stat-card stat-info">
                <i class="bi bi-file-earmark-text stat-icon"></i>
                <div class="stat-label">
                    @if ($isLawyer || $isClient) عقودي
                    @else العقود
                    @endif
                </div>
                <div class="stat-value">{{ $totalContracts }}</div>
                <div class="stat-change">
                    <i class="bi bi-calendar-check"></i> {{ $totalSessions }} جلسة
                </div>
            </div>
        </div>

        {{-- 📅 الحجوزات المعلقة (للمدير والموظف) --}}
        @if ($isAdmin || $isStaff)
            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-warning">
                    <i class="bi bi-calendar-plus stat-icon"></i>
                    <div class="stat-label">طلبات الحجز المعلّقة</div>
                    <div class="stat-value">{{ $pendingBookings }}</div>
                    <div class="stat-change">
                        <i class="bi bi-clock-history"></i> بانتظار المراجعة
                    </div>
                </div>
            </div>
        @elseif ($isLawyer)
            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-warning">
                    <i class="bi bi-calendar-check stat-icon"></i>
                    <div class="stat-label">مواعيدي</div>
                    <div class="stat-value">{{ $totalAppointments }}</div>
                    <div class="stat-change">
                        <i class="bi bi-clock-history"></i> {{ $todayAppointments }} اليوم
                    </div>
                </div>
            </div>
        @elseif ($isClient)
            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-warning">
                    <i class="bi bi-file-earmark-text stat-icon"></i>
                    <div class="stat-label">حالة قضاياي</div>
                    <div class="stat-value">{{ $activeCases }}</div>
                    <div class="stat-change">
                        <i class="bi bi-hourglass-split"></i> قيد النظر
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--              الصف الثاني: رسم بياني + جلسات              --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- 📊 رسم بياني للقضايا --}}
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart-line-fill text-warning me-2"></i>
                        @if ($isLawyer) قضاياي حسب الحالة
                        @elseif ($isClient) قضاياي حسب الحالة
                        @else نظرة عامة على القضايا
                        @endif
                    </h5>
                    <a href="{{ route('cases.index') }}" class="btn btn-sm btn-outline-secondary">
                        عرض الكل <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if ($totalCases > 0)
                        <canvas id="casesChart" height="140"></canvas>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-bar-chart fs-1 d-block mb-2 text-secondary"></i>
                            <p class="mb-0">لا توجد بيانات لعرضها</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ⚖️ الجلسات القادمة --}}
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-calendar-event-fill text-warning me-2"></i>
                        @if ($isLawyer) جلستي القادمة
                        @elseif ($isClient) جلساتي القادمة
                        @else الجلسات القادمة
                        @endif
                    </h5>
                    <span class="badge text-bg-light">{{ $upcomingSessions->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse ($upcomingSessions as $session)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="avatar-initial">
                                {{ mb_substr($session->case?->case_number ?? 'C', 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">
                                    {{ Str::limit($session->case?->case_title, 40) }}
                                </div>
                                <div class="text-muted small">
                                    <i class="bi bi-clock"></i>
                                    {{ optional($session->session_date)->translatedFormat('d M Y - h:i A') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-2"></i>
                            <p class="mb-0 mt-2">لا توجد جلسات قادمة</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--              الصف الثالث: إجراءات سريعة                  --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
                        إجراءات سريعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">

                        {{-- 👑 مدير النظام --}}
                        @if ($isAdmin)
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('cases.create') }}" class="btn btn-primary w-100 py-3">
                                    <i class="bi bi-folder-plus fs-4 d-block mb-1"></i>
                                    قضية جديدة
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('users.create') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-person-plus fs-4 d-block mb-1"></i>
                                    مستخدم جديد
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('clients.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-people fs-4 d-block mb-1"></i>
                                    إدارة الموكلين
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('financial-records.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-cash-coin fs-4 d-block mb-1"></i>
                                    السجلات المالية
                                </a>
                            </div>

                        {{-- 💼 محامي --}}
                        @elseif ($isLawyer)
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('cases.create') }}" class="btn btn-primary w-100 py-3">
                                    <i class="bi bi-folder-plus fs-4 d-block mb-1"></i>
                                    قضية جديدة
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('cases.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-folder2-open fs-4 d-block mb-1"></i>
                                    قضاياي
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-calendar-check fs-4 d-block mb-1"></i>
                                    مواعيدي
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('contracts.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-file-earmark-text fs-4 d-block mb-1"></i>
                                    عقودي
                                </a>
                            </div>

                        {{-- 📋 موظف إداري --}}
                        @elseif ($isStaff)
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('appointments.index') }}" class="btn btn-primary w-100 py-3">
                                    <i class="bi bi-calendar-check fs-4 d-block mb-1"></i>
                                    المواعيد
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-calendar-plus fs-4 d-block mb-1"></i>
                                    طلبات الحجز
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('clients.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-people fs-4 d-block mb-1"></i>
                                    الموكلون
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('contact-channels.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-chat-dots fs-4 d-block mb-1"></i>
                                    المراسلات
                                </a>
                            </div>

                        {{-- 👤 موكل --}}
                        @elseif ($isClient)
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ route('cases.index') }}" class="btn btn-primary w-100 py-3">
                                    <i class="bi bi-folder2-open fs-4 d-block mb-1"></i>
                                    قضاياي
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ route('contracts.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-file-earmark-text fs-4 d-block mb-1"></i>
                                    عقودي
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-calendar-check fs-4 d-block mb-1"></i>
                                    مواعيدي
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
@if ($totalCases > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('casesChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['قيد النظر', 'مؤجلة', 'منتهية'],
                datasets: [{
                    label: 'عدد القضايا',
                    data: [
                        {{ $activeCases }},
                        {{ (clone $casesQuery)->where('case_status', 'مؤجلة')->count() }},
                        {{ $closedCases }}
                    ],
                    backgroundColor: [
                        'rgba(240, 180, 41, 0.85)',
                        'rgba(108, 117, 125, 0.85)',
                        'rgba(25, 135, 84, 0.85)'
                    ],
                    borderColor: [
                        'rgba(240, 180, 41, 1)',
                        'rgba(108, 117, 125, 1)',
                        'rgba(25, 135, 84, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    barThickness: 50,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        rtl: true,
                        titleFont: { family: 'Cairo', size: 14 },
                        bodyFont: { family: 'Cairo', size: 13 },
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { family: 'Cairo', size: 13 },
                            precision: 0,
                            stepSize: 1,
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { font: { family: 'Cairo', size: 13, weight: '600' } },
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endif
@endpush