@extends('cms.parent')

@section('title', 'قضية جديدة')
@section('page-title', 'فتح قضية جديدة')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-folder-plus text-warning me-2"></i>
                    بيانات القضية الجديدة
                </h5>
                <small class="text-muted">جميع الحقول المميزة بـ <span class="text-danger">*</span> إلزامية</small>
            </div>

            <div class="card-body">
                <form action="{{ route('cases.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">

                        {{-- رقم القضية --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-hash text-muted me-1"></i>
                                رقم القضية <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="case_number"
                                   value="{{ old('case_number') }}"
                                   maxlength="45"
                                   placeholder="مثال: CV-12345"
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
                                   value="{{ old('case_title') }}"
                                   maxlength="45"
                                   placeholder="مثال: دعوى مطالبة بتعويض"
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
                                <option value="قيد النظر"    @selected(old('case_status') === 'قيد النظر')>قيد النظر</option>
                                <option value="مؤجلة"       @selected(old('case_status') === 'مؤجلة')>مؤجلة</option>
                                <option value="منتهية"      @selected(old('case_status') === 'منتهية')>منتهية</option>
                                <option value="مؤرشفة"      @selected(old('case_status') === 'مؤرشفة')>مؤرشفة</option>
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
                                   value="{{ old('opened_at', now()->toDateString()) }}"
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
                                    <option value="{{ $client->id }}" @selected(old('clients_id') == $client->id)>
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
                                    <option value="{{ $court->id }}" @selected(old('courts_id') == $court->id)>
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
                                    <option value="{{ $category->id }}" @selected(old('categories_id') == $category->id)>
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
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
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
                            <i class="bi bi-check-lg"></i> حفظ القضية
                        </button>
                        <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> إلغاء
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- بطاقة جانبية إرشادية --}}
    <div class="col-lg-4">
        <div class="card border-0" style="background: linear-gradient(135deg, #fffbf0 0%, #fff5d6 100%);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                    إرشادات سريعة
                </h6>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        استخدم <strong>رقم قضية فريد</strong> (لا يتكرر).
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        اختر الموكل من القائمة المنسدلة.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        التصنيف <strong>اختياري</strong> لكن يُفضَّل تحديده.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        الوصف يساعد في البحث لاحقاً.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection