<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة العقد #{{ $contract->id }}</title>
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

        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .page-title h2 {
            color: #1a2a3a;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary td {
            width: 33.33%;
            padding: 5px;
            border: none;
        }
        .summary-card {
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }
        .summary-card.total { background: #e7f3ff; border: 2px solid #0d6efd; }
        .summary-card.paid { background: #e8f5e9; border: 2px solid #198754; }
        .summary-card.remaining { background: #fff3cd; border: 2px solid #f0b429; }

        .summary-card .label {
            font-size: 11px;
            color: #495057;
            margin-bottom: 5px;
        }
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
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

        .total-row {
            background: #f8f9fb;
            font-weight: bold;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-logo">⚖</div>
        <div class="header-title">مكتب المحاماة</div>
        <div class="header-subtitle">نظام إدارة الفواتير والمدفوعات</div>
    </div>

    {{-- عنوان الفاتورة --}}
    <div class="page-title">
        <h2>فاتورة العقد: {{ $contract->contract_type }}</h2>
        <div style="color: #6c757d; font-size: 11px;">
            رقم العقد: <span class="number">#{{ $contract->id }}</span>
            &nbsp;|&nbsp;
            التاريخ: <span class="number">{{ now()->format('Y-m-d') }}</span>
        </div>
    </div>

    {{-- الملخص --}}
    <table class="summary">
        <tr>
            <td>
                <div class="summary-card total">
                    <div class="label">💰 إجمالي العقد</div>
                    <div class="value" style="color: #0d6efd;">
                        <span class="number">{{ number_format($contract->contract_value, 2) }}</span> ₪
                    </div>
                </div>
            </td>
            <td>
                <div class="summary-card paid">
                    <div class="label">✅ المدفوع</div>
                    <div class="value" style="color: #198754;">
                        <span class="number">{{ number_format($totalPaid, 2) }}</span> ₪
                    </div>
                </div>
            </td>
            <td>
                <div class="summary-card remaining">
                    <div class="label">⏳ المتبقي</div>
                    <div class="value" style="color: #d99e1f;">
                        <span class="number">{{ number_format($remaining, 2) }}</span> ₪
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- معلومات الموكل --}}
    <div class="section">
        <div class="section-title">👤 الموكل</div>
        <table>
            <tr>
                <td style="width: 35%; color: #6c757d;">الاسم</td>
                <td style="font-weight: bold;">{{ $contract->client?->user?->name ?? '—' }}</td>
            </tr>
            <tr>
                <td style="color: #6c757d;">البريد الإلكتروني</td>
                <td style="font-weight: bold;"><span class="ltr">{{ $contract->client?->user?->email ?? '—' }}</span></td>
            </tr>
            <tr>
                <td style="color: #6c757d;">رقم الهاتف</td>
                <td style="font-weight: bold;"><span class="number">{{ $contract->client?->user?->phone ?? '—' }}</span></td>
            </tr>
        </table>
    </div>

    {{-- جدول المدفوعات --}}
    <div class="section">
        <div class="section-title">💳 سجل المدفوعات ({{ $payments->count() }})</div>
        @if ($payments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 22%;">التاريخ</th>
                    <th>البيان</th>
                    <th style="width: 20%;">المبلغ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $i => $payment)
                    <tr>
                        <td><span class="number">{{ $i + 1 }}</span></td>
                        <td><span class="number">{{ $payment->created_at->format('Y-m-d') }}</span></td>
                        <td>{{ $payment->description ?? 'دفعة' }}</td>
                        <td style="color: #198754; font-weight: bold;">
                            <span class="number">{{ number_format($payment->amount, 2) }}</span> ₪
                        </td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: left;">الإجمالي المدفوع:</td>
                    <td style="color: #198754;">
                        <span class="number">{{ number_format($totalPaid, 2) }}</span> ₪
                    </td>
                </tr>
            </tbody>
        </table>
        @else
        <div style="text-align: center; padding: 20px; color: #6c757d;">
            لا توجد مدفوعات مسجلة بعد.
        </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>نظام إدارة مكتب المحاماة &copy; {{ now()->year }} — جميع الحقوق محفوظة</div>
        <div style="margin-top: 5px;">
            تم إنشاء الفاتورة في: <span class="number">{{ now()->format('Y-m-d H:i') }}</span>
        </div>
    </div>

</body>
</html>