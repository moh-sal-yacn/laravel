<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>غير مصرّح | نظام إدارة مكتب المحاماة</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
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
            color: #fff;
            position: relative;
            overflow: hidden;
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

        .container {
            max-width: 560px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(240, 180, 41, 0.2) 0%, rgba(217, 158, 31, 0.1) 100%);
            border: 3px solid rgba(240, 180, 41, 0.4);
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .icon-circle i {
            font-size: 3.5rem;
            color: #f0b429;
        }

        h1 {
            font-size: 5rem;
            font-weight: 800;
            color: #f0b429;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 20px rgba(240, 180, 41, 0.4);
        }

        h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #fff;
        }

        p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #f0b429 0%, #d99e1f 100%);
            color: #1a2a3a;
            padding: 0.85rem 1.75rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(240, 180, 41, 0.4);
            border: none;
            cursor: pointer;
            margin: 0 0.25rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240, 180, 41, 0.55);
            color: #1a2a3a;
        }

        .btn-outline {
            background: transparent;
            color: #f0b429;
            border: 2px solid #f0b429;
            box-shadow: none;
        }

        .btn-outline:hover {
            background: rgba(240, 180, 41, 0.1);
            color: #f0b429;
            box-shadow: 0 4px 15px rgba(240, 180, 41, 0.2);
        }

        .footer-note {
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
        }

        @media (max-width: 480px) {
            h1 { font-size: 3.5rem; }
            h2 { font-size: 1.25rem; }
            .icon-circle { width: 90px; height: 90px; }
            .icon-circle i { font-size: 2.5rem; }
            .btn { display: block; width: 100%; margin: 0.5rem 0; justify-content: center; }
        }
    </style>
</head>
<body>

<div class="container">

    <div class="icon-circle">
        <i class="bi bi-shield-lock-fill"></i>
    </div>

    <h1>403</h1>

    <h2>غير مصرّح لك بالوصول</h2>

    <p>
        عذراً، لا تملك الصلاحيات الكافية للوصول إلى هذه الصفحة.
        <br>
        تواصل مع <strong class="text-warning">مدير النظام</strong> إن كنت تعتقد أن هذا خطأ.
    </p>

    <div>
        <a href="{{ route('dashboard') }}" class="btn">
            <i class="bi bi-house-fill"></i>
            العودة إلى لوحة التحكم
        </a>

        <a href="javascript:history.back()" class="btn btn-outline">
            <i class="bi bi-arrow-right"></i>
            الرجوع للصفحة السابقة
        </a>
    </div>

    <div class="footer-note">
        <i class="bi bi-briefcase-fill text-warning me-1"></i>
        نظام إدارة مكتب المحاماة &copy; {{ now()->year }}
    </div>

</div>

</body>
</html>