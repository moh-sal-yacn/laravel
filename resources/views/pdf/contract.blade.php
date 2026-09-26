<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>العقد رقم {{ $contract->id }}</title>
    <style>
        body {
            font-family: 'cairo', sans-serif;
            direction: rtl;
            color: #1a2a3a;
            font-size: 11px;
            line-height: 1.8;
        }

        .ltr, .number {
            direction: ltr;
            unicode-bidi: embed;
        }

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

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
        }
        .badge-success { background: #11998e; }
        .badge-secondary { background: #6c757d; }
        .badge-danger { background: #dc3545; }

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

        .label { color: #6c757d; font-size: 11px; width: 35%; }
        .value { font-weight: bold; font-size: 12px; }

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

        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .page-title h2 {
            color: #1a2a3a;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .page-title .value-highlight {
            color: #f0b429;
            font-size: 22px;
            font-weight: bold;
        }

        .parties-box {
            background: #fffbf0;
            border: 2px solid #f0b429;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .parties-box .title {
            font-size: 13px;
            font-weight: bold;
            color: #d99e1f;
            margin-bottom: 10px;
        }
        .parties-box .content {
            font-size: 12px;
            line-height: 2;
            text-align: justify;
        }

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

        .signatures {
            margin-top: 40px;
            width: 100%;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            padding: 20px;
            border: none;
        }
        .signatures .sig-line {
            border-top: 2px solid #1a2a3a;
            margin-top: 60px;
            padding-top: 8px;
            font-size: 11px;
            color: #495057;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-logo">⚖</div>
        <div class="header-title">مكتب المحاماة</div>
        <div class="header-subtitle">نظام إدارة العقود والموكلين</div>
    </div>

    {{-- عنوان العقد --}}
    <div class="page-title">
        <h2>عقد {{ $contract->contract_type }}</h2>
        <div class="value-highlight">
            <span class="number">{{ number_format($contract->contract_value, 2) }}</span> ₪
        </div>
        <span class="badge badge-{{ ['نشط' => 'success', 'منتهي' => 'secondary', 'ملغي' => 'danger'][$contract->contract_status] ?? 'secondary' }}">
            {{ $contract->contract_status }}
        </span>
    </div>

    {{-- معلومات العقد --}}
    <div class="section">
        <div class="section-title">📋 معلومات العقد</div>
        <table>
            <tr>
                <td class="label">رقم العقد</td>
                <td class="value"><span class="number">#{{ $contract->id }}</span></td>
            </tr>
            <tr>
                <td class="label">نوع العقد</td>
                <td class="value">{{ $contract->contract_type }}</td>
            </tr>
            <tr>
                <td class="label">قيمة العقد</td>
                <td class="value"><span class="number">{{ number_format($contract->contract_value, 2) }}</span> ₪</td>
            </tr>
            <tr>
                <td class="label">الحالة</td>
                <td class="value">{{ $contract->contract_status }}</td>
            </tr>
            <tr>
                <td class="label">تاريخ التوقيع</td>
                <td class="value"><span class="number">{{ optional($contract->signed_at)->format('Y-m-d') }}</span></td>
            </tr>
        </table>
    </div>

    {{-- الأطراف --}}
    @if ($contract->parties)
    <div class="parties-box">
        <div class="title">📝 أطراف العقد</div>
        <div class="content">{{ $contract->parties }}</div>
    </div>
    @endif

    {{-- الطرفان --}}
    <table style="margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; padding: 0 5px 0 0; vertical-align: top; border: none;">
                <div class="section-title">👤 الموكل</div>
                <div class="info-box">
                    <div class="title">الاسم</div>
                    <div class="content">{{ $contract->client?->user?->name ?? '—' }}</div>
                </div>
                <div class="info-box">
                    <div class="title">البريد الإلكتروني</div>
                    <div class="content" style="font-size: 11px;">
                        <span class="ltr">{{ $contract->client?->user?->email ?? '—' }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <div class="title">رقم الهاتف</div>
                    <div class="content"><span class="number">{{ $contract->client?->user?->phone ?? '—' }}</span></div>
                </div>
            </td>
            <td style="width: 50%; padding: 0 0 0 5px; vertical-align: top; border: none;">
                <div class="section-title">💼 المحامي المسؤول</div>
                <div class="info-box">
                    <div class="title">الاسم</div>
                    <div class="content">{{ $contract->lawyer?->name ?? '—' }}</div>
                </div>
                <div class="info-box">
                    <div class="title">البريد الإلكتروني</div>
                    <div class="content" style="font-size: 11px;">
                        <span class="ltr">{{ $contract->lawyer?->email ?? '—' }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <div class="title">رقم الهاتف</div>
                    <div class="content"><span class="number">{{ $contract->lawyer?->phone ?? '—' }}</span></div>
                </div>
            </td>
        </tr>
    </table>

    {{-- التوقيعات --}}
    <table class="signatures">
        <tr>
            <td>
                <div class="sig-line">
                    <strong>توقيع الموكل</strong><br>
                    {{ $contract->client?->user?->name ?? '' }}
                </div>
            </td>
            <td>
                <div class="sig-line">
                    <strong>توقيع المحامي</strong><br>
                    {{ $contract->lawyer?->name ?? '' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div>نظام إدارة مكتب المحاماة &copy; {{ now()->year }} — جميع الحقوق محفوظة</div>
        <div style="margin-top: 5px;">
            تم إنشاء التقرير في: <span class="number">{{ now()->format('Y-m-d H:i') }}</span>
        </div>
    </div>

</body>
</html>