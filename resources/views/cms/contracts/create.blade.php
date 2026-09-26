@extends('cms.parent')

@section('title', 'عقد جديد')
@section('page-title', 'إنشاء عقد جديد')

@section('content')
<div class="row">

    {{-- النموذج --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-file-earmark-plus text-warning me-2"></i>
                    بيانات العقد الجديد
                </h5>
                <small class="text-muted">
                    الحقول المميزة بـ <span class="text-danger">*</span> إلزامية
                </small>
            </div>

            <div class="card-body">
                <form action="{{ route('contracts.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">

                        {{-- نوع العقد --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-text text-muted me-1"></i>
                                نوع العقد <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="contract_type"
                                   value="{{ old('contract_type') }}"
                                   maxlength="45"
                                   placeholder="استشارة قانونية، تمثيل قضائي..."
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
                                <option value="نشط"   @selected(old('contract_status') === 'نشط')>نشط</option>
                                <option value="منتهي" @selected(old('contract_status') === 'منتهي')>منتهي</option>
                                <option value="ملغي"  @selected(old('contract_status') === 'ملغي')>ملغي</option>
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

                        {{-- المحامي المسؤول --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-briefcase-fill text-muted me-1"></i>
                                المحامي المسؤول <span class="text-danger">*</span>
                            </label>
                            <select name="users_id" class="form-select @error('users_id') is-invalid @enderror">
                                <option value="">— اختر المحامي —</option>
                                @foreach ($lawyers as $lawyer)
                                    <option value="{{ $lawyer->id }}" @selected(old('users_id') == $lawyer->id)>
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
                                       value="{{ old('contract_value') }}"
                                       placeholder="0.00"
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
                                   value="{{ old('signed_at', now()->toDateString()) }}"
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
                                      placeholder="اذكر أطراف العقد بالتفصيل (الطرف الأول، الطرف الثاني، إلخ)..."
                                      class="form-control @error('parties') is-invalid @enderror">{{ old('parties') }}</textarea>
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
                            <i class="bi bi-check-lg"></i> حفظ العقد
                        </button>
                        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> إلغاء
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- البطاقة الجانبية --}}
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
                        <strong>نوع العقد</strong>: استشارة، تمثيل قضائي، صياغة...
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>الأطراف</strong>: مطلوب لتفادي أي نزاع مستقبلي.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>المحامي المسؤول</strong>: من سيتابع العقد.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong>تاريخ التوقيع</strong>: لا يمكن أن يكون في المستقبل.
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection