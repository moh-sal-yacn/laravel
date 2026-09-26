@extends('cms.parent')
@section('title', 'تصنيف جديد')
@section('page-title', 'إضافة تصنيف جديد')

@section('content')
<div class="row">

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-tag-fill text-warning me-2"></i>
                    بيانات التصنيف الجديد
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">

                        {{-- اسم التصنيف --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag text-muted me-1"></i>
                                اسم التصنيف <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="category_name"
                                   value="{{ old('category_name') }}"
                                   maxlength="45"
                                   placeholder="مثال: قضايا مدنية"
                                   class="form-control @error('category_name') is-invalid @enderror">
                            @error('category_name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- التصنيف الأب --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-diagram-3 text-muted me-1"></i>
                                التصنيف الأب <small class="text-muted">(اختياري)</small>
                            </label>
                            <select name="categories_id" class="form-select @error('categories_id') is-invalid @enderror">
                                <option value="">— بدون تصنيف أب —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('categories_id') == $cat->id)>
                                        {{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories_id')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                يمكنك ترك هذا الحقل فارغاً لتصنيف رئيسي، أو اختيار تصنيف أب لتصنيف فرعي.
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> حفظ التصنيف
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
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
                        <strong>التصنيف الرئيسي</strong>: مثل "قضايا مدنية"، اترك "التصنيف الأب" فارغاً.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>التصنيف الفرعي</strong>: مثل "عقود بيع"، اختر "قضايا مدنية" كأب.
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection