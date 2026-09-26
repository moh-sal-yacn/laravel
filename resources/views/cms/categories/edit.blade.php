@extends('cms.parent')
@section('title', 'تعديل تصنيف')
@section('page-title', 'تعديل التصنيف')

@section('content')
<div class="row">

    <div class="col-12 mb-3">
        <div class="card border-0" style="background: linear-gradient(135deg, #1a2a3a 0%, #0f1c2a 100%); color:#fff;">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3">
                <div>
                    <small class="text-white-50 d-block mb-1">
                        <i class="bi bi-pencil-square me-1"></i> تعديل بيانات التصنيف
                    </small>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-tag-fill text-warning me-2"></i>
                        {{ $category->category_name }}
                    </h5>
                </div>
                <span class="badge text-bg-light status-badge" style="font-family: monospace;">
                    #{{ $category->id }}
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
                <form action="{{ route('categories.update', $category) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag text-muted me-1"></i>
                                اسم التصنيف <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="category_name"
                                   value="{{ old('category_name', $category->category_name) }}"
                                   maxlength="45"
                                   class="form-control @error('category_name') is-invalid @enderror">
                            @error('category_name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-diagram-3 text-muted me-1"></i>
                                التصنيف الأب <small class="text-muted">(اختياري)</small>
                            </label>
                            <select name="categories_id" class="form-select @error('categories_id') is-invalid @enderror">
                                <option value="">— بدون تصنيف أب —</option>
                                @foreach ($categories as $cat)
                                    @if ($cat->id !== $category->id)
                                        <option value="{{ $cat->id }}" @selected(old('categories_id', $category->categories_id) == $cat->id)>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('categories_id')
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
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
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
                    معلومات التصنيف
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between small">
                    <span class="text-muted">المعرّف</span>
                    <span class="fw-semibold" style="font-family: monospace;">#{{ $category->id }}</span>
                </div>
                <div class="p-3 d-flex justify-content-between small">
                    <span class="text-muted">الاسم</span>
                    <span class="fw-semibold text-end">{{ $category->category_name }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection