@extends('cms.parent')
@section('title', 'محكمة جديدة')
@section('page-title', 'إضافة محكمة جديدة')

@section('content')
<div class="row">

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-bank2 text-warning me-2"></i>
                    بيانات المحكمة الجديدة
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('courts.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">

                        {{-- اسم المحكمة --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-bank text-muted me-1"></i>
                                اسم المحكمة <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="court_name"
                                   value="{{ old('court_name') }}"
                                   maxlength="45"
                                   placeholder="مثال: محكمة صلح غزة"
                                   class="form-control @error('court_name') is-invalid @enderror">
                            @error('court_name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الاختصاص --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-diagram-3 text-muted me-1"></i>
                                الاختصاص <span class="text-danger">*</span>
                            </label>
                            <select name="jurisdiction" class="form-select @error('jurisdiction') is-invalid @enderror">
                                <option value="">— اختر الاختصاص —</option>
                                @foreach (['مدني', 'جزائي', 'تجاري', 'أحوال شخصية', 'عمالي', 'إداري'] as $j)
                                    <option value="{{ $j }}" @selected(old('jurisdiction') === $j)>{{ $j }}</option>
                                @endforeach
                            </select>
                            @error('jurisdiction')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- الموقع --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt-fill text-muted me-1"></i>
                                الموقع <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="location"
                                   value="{{ old('location') }}"
                                   maxlength="45"
                                   placeholder="مثال: غزة"
                                   class="form-control @error('location') is-invalid @enderror">
                            @error('location')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> حفظ المحكمة
                        </button>
                        <a href="{{ route('courts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> إلغاء
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0" style="background: linear-gradient(135deg, #fffbf0 0%, #fff5d6 100%);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                    إرشادات سريعة
                </h6>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>اسم المحكمة</strong>: واضح ومحدد (مثال: محكمة صلح غزة).
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>الاختصاص</strong>: نوع القضايا التي تنظرها.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>الموقع</strong>: المدينة أو المنطقة.
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection