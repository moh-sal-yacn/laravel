@extends('cms.parent')
@section('title', 'تعديل عقد')
@section('page-title', 'تعديل بيانات العقد')

@section('content')
<div class="card col-lg-7">
    <div class="card-body">
        <form action="{{ route('contracts.update', $contract) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">نوع العقد</label>
                    <input type="text" name="contract_type" class="form-control" value="{{ old('contract_type', $contract->contract_type) }}" maxlength="45" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحالة</label>
                    <select name="contract_status" class="form-select" required>
                        @foreach (['نشط', 'منتهي', 'ملغي'] as $status)
                            <option value="{{ $status }}" @selected(old('contract_status', $contract->contract_status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الموكل</label>
                    <select name="clients_id" class="form-select" required>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('clients_id', $contract->clients_id) == $client->id)>{{ $client->user?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">المحامي المسؤول</label>
                    <select name="users_id" class="form-select" required>
                        @foreach ($lawyers as $lawyer)
                            <option value="{{ $lawyer->id }}" @selected(old('users_id', $contract->users_id) == $lawyer->id)>{{ $lawyer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">قيمة العقد</label>
                    <input type="number" step="0.01" name="contract_value" class="form-control" value="{{ old('contract_value', $contract->contract_value) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ التوقيع</label>
                    <input type="date" name="signed_at" class="form-control" value="{{ old('signed_at', $contract->signed_at->format('Y-m-d')) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">الأطراف</label>
                    <textarea name="parties" class="form-control" rows="2" required>{{ old('parties', $contract->parties) }}</textarea>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">حفظ التعديلات</button>
                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
