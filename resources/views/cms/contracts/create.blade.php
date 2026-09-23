@extends('cms.parent')
@section('title', 'عقد جديد')
@section('page-title', 'إنشاء عقد جديد')

@section('content')
<div class="card col-lg-7">
    <div class="card-body">
        <form action="{{ route('contracts.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">نوع العقد</label>
                    <input type="text" name="contract_type" class="form-control" value="{{ old('contract_type') }}" maxlength="45" required placeholder="استشارة قانونية، تمثيل قضائي...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحالة</label>
                    <select name="contract_status" class="form-select" required>
                        <option value="نشط">نشط</option>
                        <option value="منتهي">منتهي</option>
                        <option value="ملغي">ملغي</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الموكل</label>
                    <select name="clients_id" class="form-select" required>
                        <option value="">— اختر —</option>
                        @foreach ($clients as $client)<option value="{{ $client->id }}">{{ $client->user?->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">المحامي المسؤول</label>
                    <select name="users_id" class="form-select" required>
                        <option value="">— اختر —</option>
                        @foreach ($lawyers as $lawyer)<option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">قيمة العقد</label>
                    <input type="number" step="0.01" name="contract_value" class="form-control" value="{{ old('contract_value') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ التوقيع</label>
                    <input type="date" name="signed_at" class="form-control" value="{{ old('signed_at', now()->toDateString()) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">الأطراف</label>
                    <textarea name="parties" class="form-control" rows="2" required>{{ old('parties') }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">حفظ</button>
                <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
