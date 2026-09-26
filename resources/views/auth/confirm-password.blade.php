<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>تأكيد كلمة المرور | نظام إدارة مكتب المحاماة</title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    {{-- خط Cairo --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cairo', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before, body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(240, 180, 41, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        body::before { width: 500px; height: 500px; top: -150px; right: -150px; }
        body::after  { width: 400px; height: 400px; bottom: -100px; left: -100px; }

        .login-wrapper {
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
        }

        /* ─── الشعار ─── */
        .brand-card {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, #f0b429 0%, #d99e1f 100%);
            box-shadow: 0 10px 30px rgba(240, 180, 41, 0.4);
            margin-bottom: 1rem;
        }

        .brand-logo i { font-size: 2.5rem; color: #1a2a3a; }
        .brand-title { color: #fff; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; }
        .brand-subtitle { color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; }

        /* ─── البطاقة ─── */
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-card h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a2a3a;
            margin-bottom: 0.5rem;
        }

        .welcome-text {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .text-center { text-align: center; }

        /* ─── أيقونة إرشادية ─── */
        .info-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(240, 180, 41, 0.15);
            color: #d99e1f;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* ─── الحقول ─── */
        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .form-label i { color: #f0b429; margin-left: 0.35rem; }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #f0b429;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(240, 180, 41, 0.15);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* ─── صندوق تحذيري ─── */
        .security-note {
            background: #fef9e7;
            border-right: 4px solid #f0b429;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #78350f;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .security-note i {
            color: #d99e1f;
            font-size: 1.2rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ─── زر ─── */
        .btn-login {
            width: 100%;
            padding: 0.85rem 1rem;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #f0b429 0%, #d99e1f 100%);
            color: #1a2a3a;
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(240, 180, 41, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240, 180, 41, 0.55);
        }

        /* ─── رابط العودة ─── */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.9rem;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .back-link:hover { color: #f0b429; }

        /* ─── تنبيه ─── */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-right: 4px solid #ef4444;
        }

        /* ─── الفوتر ─── */
        .page-footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 480px) {
            .login-card { padding: 1.5rem; }
            .brand-logo { width: 65px; height: 65px; }
            .brand-logo i { font-size: 2rem; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- ═══════ الشعار ═══════ --}}
    <div class="brand-card">
        <div class="brand-logo">
            <i class="bi bi-briefcase-fill"></i>
        </div>
        <h1 class="brand-title">مكتب المحاماة</h1>
        <p class="brand-subtitle">نظام إدارة القضايا والموكلين</p>
    </div>

    {{-- ═══════ بطاقة تأكيد كلمة المرور ═══════ --}}
    <div class="login-card">

        <div class="text-center">
            <div class="info-icon">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>

        <h2 class="text-center">تأكيد كلمة المرور</h2>
        <p class="welcome-text text-center">
            هذه منطقة آمنة في النظام. يُرجى تأكيد كلمة المرور للمتابعة.
        </p>

        {{-- صندوق تحذيري --}}
        <div class="security-note">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <strong>ملاحظة أمنية:</strong>
                هذا الإجراء مطلوب قبل تنفيذ عمليات حساسة لحماية حسابك.
            </div>
        </div>

        {{-- ❌ رسائل الأخطاء --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}" novalidate>
            @csrf

            {{-- كلمة المرور --}}
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="bi bi-lock-fill"></i>
                    كلمة المرور
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-input @error('password') is-invalid @enderror"
                       placeholder="••••••••"
                       dir="ltr"
                       required
                       autofocus
                       autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- زر التأكيد --}}
            <button type="submit" class="btn-login">
                <i class="bi bi-check-circle-fill"></i>
                تأكيد
            </button>

        </form>

        {{-- رابط العودة --}}
        <a href="{{ url()->previous() }}" class="back-link">
            <i class="bi bi-arrow-right"></i>
            العودة إلى الصفحة السابقة
        </a>

    </div>

    {{-- ═══════ الفوتر ═══════ --}}
    <div class="page-footer">
        <i class="bi bi-briefcase-fill text-warning me-1"></i>
        نظام إدارة مكتب المحاماة &copy; {{ now()->year }}
        <br>
        جميع الحقوق محفوظة
    </div>

</div>

</body>
</html>