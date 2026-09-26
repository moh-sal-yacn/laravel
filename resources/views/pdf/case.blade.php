<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل القضية {{ $case->case_number }}</title>
    <style>
        /* ═══════ الأساسيات ═══════ */
        body {
            font-family: 'cairo', sans-serif;
            direction: rtl;
            color: #1a2a3a;
            font-size: 11px;
            line-height: 1.8;
        }

        /* الأرقام والإيميلات تبقى LTR */
        .ltr, .number {
            direction: ltr;
            unicode-bidi: embed;
        }

        /* ═══════ Header ═══════ */
        .header {
            background: #1a2a3a;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .header-logo {
            display: inline-block;
            background: #f0b429;
            color: #1a2a3a;
            width: 50px;
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 24px;
            border-radius: 10px;
            margin-left: 15px;
            float: right;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #f0b429;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
        }

        /* ═══════ Badges ═══════ */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
        }

        .badge-warning { background: #f0b429; color: #1a2a3a; }
        .badge-success { background: #11998e; }
        .badge-secondary { background: #6c757d; }
        .badge-dark { background: #1a2a3a; }

        /* ═══════ Sections ═══════ */
        .section { margin-bottom: 20px; }

        .section-title {
            background: #f8f9fb;
            color: #1a2a3a;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            border-right: 4px solid #f0b429;
            margin-bottom: 12px;
        }

        /* ═══════ Tables ═══════ */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td, table th {
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
            text-align: right;
        }

        table th {
            background: #f8f9fb;
            font-weight: bold;
            font-size: 11px;
            color: #495057;
        }

        .label {
            color: #6c757d;
            font-size: 11px;
            width: 35%;
        }

        .value {
            font-weight: bold;
            font-size: 12px;
        }

        /* ═══════ Info Boxes ═══════ */
        .info-box {
            background: #f8f9fb;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .info-box .title {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .info-box .content {
            font-size: 13px;
            font-weight: bold;
        }

        /* ═══════ Title Center ═══════ */
        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .page-title h2 {
            color: #1a2a3a;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .page-title .case-number {
            color: #f0b429;
        }

        /* ═══════ Footer ═══════ */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 10px;
            border-top: 1px solid #e9ecef;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- ═══════ Header ═══════ --}}
    <div class="header">
        <div class="header-logo">⚖</div>
        <div class="header-title">مكتب المحاماة</div>
        <div class="header-subtitle">نظام إدارة القضايا والموكلين</div>
    </div>

    {{-- ═══════ عنوان التقرير ═══════ --}}
    <div class="page-title">
        <h2>
            ملف القضية رقم:
            <span class="case-number">{{ $case->case_number }}</span>
        </h2>
        <span class="badge badge-{{ ['قيد النظر' => 'warning', 'مؤجلة' => 'secondary', 'منتهية' => 'success', 'مؤرشفة' => 'dark'][$case->case_status] ?? 'secondary' }}">
            {{ $case->case_status }}
        </span>
    </div>

    {{-- ═══════ معلومات أساسية ═══════ --}}
    <div class="section">
        <div class="section-title">📋 معلومات أساسية</div>
        <table>
            <tr>
                <td class="label">رقم القضية</td>
                <td class="value"><span class="number">{{ $case->case_number }}</span></td>
            </tr>
            <tr>
                <td class="label">عنوان القضية</td>
                <td class="value">{{ $case->case_title }}</td>
            </tr>
            <tr>
                <td class="label">التصنيف</td>
                <td class="value">{{ $case->category?->category_name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">الحالة</td>
                <td class="value">{{ $case->case_status }}</td>
            </tr>
            <tr>
                <td class="label">تاريخ الفتح</td>
                <td class="value"><span class="number">{{ optional($case->opened_at)->format('Y-m-d') }}</span></td>
            </tr>
        </table>
    </div>

    {{-- ═══════ الموكل + المحكمة ═══════ --}}
    <table style="margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; padding: 0 5px 0 0; vertical-align: top; border: none;">
                <div class="section-title">👤 الموكل</div>
                <div class="info-box">
                    <div class="title">الاسم</div>
                    <div class="content">{{ $case->client?->user?->name ?? '—' }}</div>
                </div>
                <div class="info-box">
                    <div class="title">البريد الإلكتروني</div>
                    <div class="content" style="font-size: 11px;">
                        <span class="ltr">{{ $case->client?->user?->email ?? '—' }}</span>
                    </div>
                </div>
            </td>
            <td style="width: 50%; padding: 0 0 0 5px; vertical-align: top; border: none;">
                <div class="section-title">🏛️ المحكمة</div>
                <div class="info-box">
                    <div class="title">اسم المحكمة</div>
                    <div class="content">{{ $case->court?->court_name ?? '—' }}</div>
                </div>
                <div class="info-box">
                    <div class="title">الاختصاص</div>
                    <div class="content">{{ $case->court?->jurisdiction ?? '—' }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ═══════ الوصف ═══════ --}}
    @if ($case->description)
    <div class="section">
        <div class="section-title">📝 وصف القضية</div>
        <div style="padding: 10px; line-height: 1.9;">
            {{ $case->description }}
        </div>
    </div>
    @endif

    {{-- ═══════ أطراف القضية ═══════ --}}
    @if ($case->participants->count() > 0)
    <div class="section">
        <div class="section-title">👥 أطراف القضية ({{ $case->participants->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">#</th>
                    <th>الاسم</th>
                    <th>الدور</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($case->participants as $i => $participant)
                    <tr>
                        <td><span class="number">{{ $i + 1 }}</span></td>
                        <td>{{ $participant->name }}</td>
                        <td>{{ $participant->pivot->role_in_case }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ═══════ الجلسات ═══════ --}}
    @if ($case->sessions->count() > 0)
    <div class="section">
        <div class="section-title">⚖️ الجلسات ({{ $case->sessions->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 22%;">التاريخ</th>
                    <th style="width: 30%;">المحامي</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($case->sessions as $i => $session)
                    <tr>
                        <td><span class="number">{{ $i + 1 }}</span></td>
                        <td><span class="number">{{ $session->session_date->format('Y-m-d H:i') }}</span></td>
                        <td>{{ $session->user?->name ?? '—' }}</td>
                        <td>{{ Str::limit($session->session_notes ?? '—', 80) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ═══════ Footer ═══════ --}}
    <div class="footer">
        <div>نظام إدارة مكتب المحاماة &copy; {{ now()->year }} — جميع الحقوق محفوظة</div>
        <div style="margin-top: 5px;">
            تم إنشاء التقرير في: <span class="number">{{ now()->format('Y-m-d H:i') }}</span>
        </div>
    </div>

</body>
</html>