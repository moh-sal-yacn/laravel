@extends('cms.parent')

@section('title', 'تعديل قضية')
@section('page-title', 'تعديل القضية')

@php
    $statusColors = [
        'قيد النظر' => 'warning',
        'مؤجلة'    => 'secondary',
        'منتهية'   => 'success',
        'مؤرشفة'   => 'dark',
    ];
@endphp

@section('content')
<div class="row">

    {{-- شريط علوي: رقم القضية + الحالة --}}
    <div class="col-12 mb-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
                <div>
                    <small class="text-white-50 d-block mb-1">
                        <i class="bi bi-pencil-square me-1"></i> تعديل القضية
                    </small>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-folder2-open text-warning me-2"></i>
                        {{ $case->case_number }}
                        <small class="text-white-50 ms-2">— {{ Str::limit($case->case_title, 50) }}</small>
                    </h5>
                </div>
                <div class="text-end">
                    <span class="badge rounded-pill text-bg-{{ $statusColors[$case->case_status] ?? 'light' }} status-badge">
                        {{ $case->case_status }}
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
                    تعديل بيانات القضية
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('cases.update', $case) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- رقم القضية --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-hash text-muted me-1"></i>
                                رقم القضية <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="case_number"
                                   value="{{ old('case_number', $case->case_number) }}"
                                   maxlength="45"
                                   class="form-control @error('case_number') is-invalid @enderror">
                            @error('case_number')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- عنوان القضية --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-bookmark text-muted me-1"></i>
                                عنوان القضية <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="case_title"
                                   value="{{ old('case_title', $case->case_title) }}"
                                   maxlength="45"
                                   class="form-control @error('case_title') is-invalid @enderror">
                            @error('case_title')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الحالة --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-flag text-muted me-1"></i>
                                حالة القضية <span class="text-danger">*</span>
                            </label>
                            <select name="case_status" class="form-select @error('case_status') is-invalid @enderror">
                                @foreach (['قيد النظر', 'مؤجلة', 'منتهية', 'مؤرشفة'] as $status)
                                    <option value="{{ $status }}" @selected(old('case_status', $case->case_status) === $status)>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('case_status')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- تاريخ الفتح --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar3 text-muted me-1"></i>
                                تاريخ الفتح <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   name="opened_at"
                                   value="{{ old('opened_at', $case->opened_at->format('Y-m-d')) }}"
                                   class="form-control @error('opened_at') is-invalid @enderror">
                            @error('opened_at')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الموكل --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-muted me-1"></i>
                                الموكل <span class="text-danger">*</span>
                            </label>
                            <select name="clients_id" class="form-select @error('clients_id') is-invalid @enderror">
                                <option value="">— اختر الموكل —</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('clients_id', $case->clients_id) == $client->id)>
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

                        {{-- المحكمة --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-bank2 text-muted me-1"></i>
                                المحكمة <span class="text-danger">*</span>
                            </label>
                            <select name="courts_id" class="form-select @error('courts_id') is-invalid @enderror">
                                <option value="">— اختر المحكمة —</option>
                                @foreach ($courts as $court)
                                    <option value="{{ $court->id }}" @selected(old('courts_id', $case->courts_id) == $court->id)>
                                        {{ $court->court_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('courts_id')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- التصنيف --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag text-muted me-1"></i>
                                التصنيف <small class="text-muted">(اختياري)</small>
                            </label>
                            <select name="categories_id" class="form-select @error('categories_id') is-invalid @enderror">
                                <option value="">— بدون تصنيف —</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('categories_id', $case->categories_id) == $category->id)>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories_id')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الوصف --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-file-text text-muted me-1"></i>
                                وصف القضية <small class="text-muted">(اختياري)</small>
                            </label>
                            <textarea name="description"
                                      rows="4"
                                      placeholder="اكتب تفاصيل وملابسات القضية هنا..."
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $case->description) }}</textarea>
                            @error('description')
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
                        <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> إلغاء
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- البطاقة الجانبية: معلومات القضية --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle-fill text-warning me-1"></i>
                    معلومات القضية الحالية
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">رقم القضية</span>
                    <span class="fw-semibold" style="font-family: monospace;">{{ $case->case_number }}</span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">الحالة</span>
                    <span class="badge text-bg-{{ $statusColors[$case->case_status] ?? 'light' }} status-badge">
                        {{ $case->case_status }}
                    </span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">تاريخ الفتح</span>
                    <span class="fw-semibold">{{ $case->opened_at->format('Y-m-d') }}</span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">الموكل</span>
                    <span class="fw-semibold text-end">{{ $case->client?->user?->name ?? '—' }}</span>
                </div>
                <div class="p-3 d-flex justify-content-between small">
                    <span class="text-muted">المحكمة</span>
                    <span class="fw-semibold text-end">{{ $case->court?->court_name ?? '—' }}</span>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('cases.show', $case) }}" class="btn btn-sm btn-outline-primary w-100">
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
                    تأكد من مراجعة التعديلات قبل الحفظ. بعض التغييرات (مثل تغيير الموكل) قد تؤثر على بيانات مرتبطة.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection