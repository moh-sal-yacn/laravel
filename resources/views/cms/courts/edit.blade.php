@extends('cms.parent')
@section('title', 'تعديل محكمة')
@section('page-title', 'تعديل بيانات المحكمة')

@section('content')
<div class="row">

    <div class="col-12 mb-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
                <div>
                    <small class="text-white-50 d-block mb-1">
                        <i class="bi bi-pencil-square me-1"></i> تعديل بيانات المحكمة
                    </small>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bank2 text-warning me-2"></i>
                        {{ $court->court_name }}
                    </h5>
                </div>
                <span class="badge text-bg-info status-badge">
                    {{ $court->jurisdiction }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square text-warning me-2"></i>
                    تعديل البيانات
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('courts.update', $court) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-bank text-muted me-1"></i>
                                اسم المحكمة <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="court_name"
                                   value="{{ old('court_name', $court->court_name) }}"
                                   maxlength="45"
                                   class="form-control @error('court_name') is-invalid @enderror">
                            @error('court_name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-diagram-3 text-muted me-1"></i>
                                الاختصاص <span class="text-danger">*</span>
                            </label>
                            <select name="jurisdiction" class="form-select @error('jurisdiction') is-invalid @enderror">
                                <option value="">— اختر الاختصاص —</option>
                                @foreach (['مدني', 'جزائي', 'تجاري', 'أحوال شخصية', 'عمالي', 'إداري'] as $j)
                                    <option value="{{ $j }}" @selected(old('jurisdiction', $court->jurisdiction) === $j)>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jurisdiction')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt-fill text-muted me-1"></i>
                                الموقع <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="location"
                                   value="{{ old('location', $court->location) }}"
                                   maxlength="45"
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
                            <i class="bi bi-check-lg"></i> حفظ التعديلات
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
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle-fill text-warning me-1"></i>
                    معلومات المحكمة الحالية
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">الاسم</span>
                    <span class="fw-semibold text-end">{{ $court->court_name }}</span>
                </div>
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">الاختصاص</span>
                    <span class="badge text-bg-info status-badge">{{ $court->jurisdiction }}</span>
                </div>
                <div class="p-3 d-flex justify-content-between small">
                    <span class="text-muted">الموقع</span>
                    <span class="fw-semibold text-end">{{ $court->location ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection