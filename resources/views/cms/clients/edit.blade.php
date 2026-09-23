@extends('cms.parent')
@section('title', 'تعديل موكل')
@section('page-title', 'تعديل بيانات الموكل')

@section('content')
<div class="card col-lg-6">
    <div class="card-body">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">الاسم</label>
                <input type="text" class="form-control" value="{{ $client->user?->name }}" disabled>
                <div class="form-text">لتعديل الاسم أو البريد، انتقل إلى صفحة المستخدمين.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">نوع الموكل</label>
                <select name="client_kind" class="form-select" required>
                    <option value="individual" @selected(old('client_kind', $client->client_kind) === 'individual')>فرد</option>
                    <option value="company" @selected(old('client_kind', $client->client_kind) === 'company')>شركة</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">الرقم الوطني</label>
                <input type="text" name="national_id" class="form-control" value="{{ old('national_id', $client->national_id) }}" maxlength="45">
            </div>
            <button class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
