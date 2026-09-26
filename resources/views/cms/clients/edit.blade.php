@extends('cms.parent')
@section('title', 'تعديل موكل')
@section('page-title', 'تعديل بيانات الموكل')

@php
    $kindColors = [
        'individual' => ['label' => 'فرد',  'color' => 'info',    'icon' => 'bi-person'],
        'company'    => ['label' => 'شركة', 'color' => 'primary', 'icon' => 'bi-building'],
    ];
    $kind = $kindColors[$client->client_kind] ?? ['label' => $client->client_kind, 'color' => 'light', 'icon' => 'bi-question'];
@endphp

@section('content')
<div class="row">

    {{-- شريط علوي --}}
    <div class="col-12 mb-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <span class="avatar-initial me-3" style="width:50px;height:50px;font-size:1.4rem;">
                        {{ mb_substr($client->user?->name ?? '?', 0, 1) }}
                    </span>
                    <div>
                        <small class="text-white-50 d-block mb-1">
                            <i class="bi bi-pencil-square me-1"></i> تعديل بيانات الموكل
                        </small>
                        <h5 class="mb-0 fw-bold">{{ $client->user?->name }}</h5>
                        <small class="text-white-50">{{ $client->user?->email }}</small>
                    </div>
                </div>
                <span class="badge text-bg-{{ $kind['color'] }} status-badge">
                    <i class="bi {{ $kind['icon'] }} me-1"></i>{{ $kind['label'] }}
                </span>
            </div>
        </div>
    </div>

    {{-- النموذج --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square text-warning me-2"></i>
                    بيانات الموكل
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('clients.update', $client) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- الاسم (معطّل) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-muted me-1"></i>
                                الاسم الكامل
                            </label>
                            <input type="text"
                                   value="{{ $client->user?->name }}"
                                   class="form-control"
                                   disabled>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                لتعديل الاسم أو البريد، انتقل إلى
                                <a href="{{ route('users.edit', $client->users_id) }}">صفحة المستخدم</a>.
                            </div>
                        </div>

                        {{-- البريد (معطّل) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill text-muted me-1"></i>
                                البريد الإلكتروني
                            </label>
                            <input type="email"
                                   value="{{ $client->user?->email }}"
                                   dir="ltr"
                                   class="form-control"
                                   disabled>
                        </div>

                        {{-- نوع الموكل --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge-fill text-muted me-1"></i>
                                نوع الموكل <span class="text-danger">*</span>
                            </label>
                            <select name="client_kind" class="form-select @error('client_kind') is-invalid @enderror">
                                <option value="individual" @selected(old('client_kind', $client->client_kind) === 'individual')>
                                    فرد
                                </option>
                                <option value="company" @selected(old('client_kind', $client->client_kind) === 'company')>
                                    شركة
                                </option>
                            </select>
                            @error('client_kind')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الرقم الوطني --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-card-text text-muted me-1"></i>
                                الرقم الوطني <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="national_id"
                                   value="{{ old('national_id', $client->national_id) }}"
                                   maxlength="45"
                                   dir="ltr"
                                   style="font-family: monospace;"
                                   class="form-control @error('national_id') is-invalid @enderror">
                            @error('national_id')
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
                        <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary">
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
                    معلومات الموكل
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">النوع</span>
                    <span class="badge text-bg-{{ $kind['color'] }} status-badge">
                        <i class="bi {{ $kind['icon'] }} me-1"></i>{{ $kind['label'] }}
                    </span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">الهاتف</span>
                    <span class="fw-semibold" dir="ltr">{{ $client->user?->phone ?? '—' }}</span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">عدد القضايا</span>
                    <span class="fw-semibold">{{ $client->cases->count() }}</span>
                </div>
                <div class="p-3 d-flex justify-content-between small">
                    <span class="text-muted">عدد العقود</span>
                    <span class="fw-semibold">{{ $client->contracts->count() }}</span>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-eye"></i> عرض الملف الكامل
                </a>
            </div>
        </div>
    </div>

</div>
@endsection