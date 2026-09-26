@extends('cms.parent')

@section('title', 'تعديل عقد')
@section('page-title', 'تعديل بيانات العقد')

@php
    $statusColors = [
        'نشط'   => 'success',
        'منتهي' => 'secondary',
        'ملغي'  => 'danger',
    ];
@endphp

@section('content')
<div class="row">

    {{-- شريط علوي --}}
    <div class="col-12 mb-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
                <div>
                    <small class="text-white-50 d-block mb-1">
                        <i class="bi bi-pencil-square me-1"></i> تعديل العقد رقم #{{ $contract->id }}
                    </small>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-file-earmark-text text-warning me-2"></i>
                        {{ $contract->contract_type }}
                    </h5>
                </div>
                <div class="text-end">
                    <span class="badge rounded-pill text-bg-{{ $statusColors[$contract->contract_status] ?? 'light' }} status-badge">
                        {{ $contract->contract_status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- النموذج --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square text-warning me-2"></i>
                    تعديل بيانات العقد
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('contracts.update', $contract) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- نوع العقد --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-text text-muted me-1"></i>
                                نوع العقد <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="contract_type"
                                   value="{{ old('contract_type', $contract->contract_type) }}"
                                   maxlength="45"
                                   class="form-control @error('contract_type') is-invalid @enderror">
                            @error('contract_type')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الحالة --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-flag text-muted me-1"></i>
                                حالة العقد <span class="text-danger">*</span>
                            </label>
                            <select name="contract_status" class="form-select @error('contract_status') is-invalid @enderror">
                                @foreach (['نشط', 'منتهي', 'ملغي'] as $status)
                                    <option value="{{ $status }}" @selected(old('contract_status', $contract->contract_status) === $status)>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_status')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الموكل --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-muted me-1"></i>
                                الموكل <span class="text-danger">*</span>
                            </label>
                            <select name="clients_id" class="form-select @error('clients_id') is-invalid @enderror">
                                <option value="">— اختر الموكل —</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('clients_id', $contract->clients_id) == $client->id)>
                                        {{ $client->user?->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('clients_id')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- المحامي المسؤول --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-briefcase-fill text-muted me-1"></i>
                                المحامي المسؤول <span class="text-danger">*</span>
                            </label>
                            <select name="users_id" class="form-select @error('users_id') is-invalid @enderror">
                                <option value="">— اختر المحامي —</option>
                                @foreach ($lawyers as $lawyer)
                                    <option value="{{ $lawyer->id }}" @selected(old('users_id', $contract->users_id) == $lawyer->id)>
                                        {{ $lawyer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('users_id')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- قيمة العقد --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-cash-coin text-muted me-1"></i>
                                قيمة العقد <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       name="contract_value"
                                       value="{{ old('contract_value', $contract->contract_value) }}"
                                       class="form-control @error('contract_value') is-invalid @enderror">
                                <span class="input-group-text fw-bold">₪</span>
                                @error('contract_value')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- تاريخ التوقيع --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar3 text-muted me-1"></i>
                                تاريخ التوقيع <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   name="signed_at"
                                   value="{{ old('signed_at', $contract->signed_at->format('Y-m-d')) }}"
                                   class="form-control @error('signed_at') is-invalid @enderror">
                            @error('signed_at')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الأطراف --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-people-fill text-muted me-1"></i>
                                أطراف العقد <span class="text-danger">*</span>
                            </label>
                            <textarea name="parties"
                                      rows="3"
                                      class="form-control @error('parties') is-invalid @enderror">{{ old('parties', $contract->parties) }}</textarea>
                            @error('parties')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    {{-- أزرار --}}
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> حفظ التعديلات
                        </button>
                        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> إلغاء
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- البطاقة الجانبية --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle-fill text-warning me-1"></i>
                    معلومات العقد الحالي
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">رقم العقد</span>
                    <span class="fw-semibold" style="font-family: monospace;">#{{ $contract->id }}</span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">الحالة</span>
                    <span class="badge text-bg-{{ $statusColors[$contract->contract_status] ?? 'light' }} status-badge">
                        {{ $contract->contract_status }}
                    </span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">القيمة</span>
                    <span class="fw-semibold text-success">
                        {{ number_format($contract->contract_value, 2) }} ₪
                    </span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">تاريخ التوقيع</span>
                    <span class="fw-semibold">{{ $contract->signed_at->format('Y-m-d') }}</span>
                </div>
                <div class="p-3 d-flex justify-content-between small">
                    <span class="text-muted">المحامي</span>
                    <span class="fw-semibold text-end">{{ $contract->lawyer?->name ?? '—' }}</span>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> عرض التفاصيل الكاملة
                </a>
            </div>
        </div>

        {{-- تحذير --}}
        <div class="card border-0 mt-3" style="background: #fff5f5;">
            <div class="card-body">
                <h6 class="fw-bold text-danger mb-2">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    تنبيه
                </h6>
                <p class="small text-muted mb-0">
                    تغيير <strong>الموكل</strong> سيؤثر على ربط المدفوعات. تأكد من صحة التعديل قبل الحفظ.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection