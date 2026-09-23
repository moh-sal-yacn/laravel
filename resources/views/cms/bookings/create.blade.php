@extends('cms.parent')
@section('title', 'حجز جديد')
@section('page-title', 'إضافة طلب حجز')

@section('content')
<div class="card col-lg-6">
    <div class="card-body">
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">اسم الزائر</label>
                <input type="text" name="visitor_name" class="form-control" value="{{ old('visitor_name') }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label class="form-label">نوع الحجز</label>
                <select name="booking_type" class="form-select" required>
                    <option value="استشارة">استشارة</option>
                    <option value="متابعة قضية">متابعة قضية</option>
                    <option value="توقيع عقد">توقيع عقد</option>
                    <option value="أخرى">أخرى</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">الموعد المفضل</label>
                <input type="datetime-local" name="preferred_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select" required>
                    <option value="قيد الانتظار">قيد الانتظار</option>
                    <option value="مؤكد">مؤكد</option>
                    <option value="ملغي">ملغي</option>
                    <option value="مكتمل">مكتمل</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">المحامي (اختياري)</label>
                <select name="users_id" class="form-select">
                    <option value="">— بدون —</option>
                    @foreach ($lawyers as $lawyer)<option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>@endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">الموكل (اختياري)</label>
                <select name="clients_id" class="form-select">
                    <option value="">— بدون —</option>
                    @foreach ($clients as $client)<option value="{{ $client->id }}">{{ $client->user?->name }}</option>@endforeach
                </select>
            </div>
            <button class="btn btn-primary">حفظ</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
