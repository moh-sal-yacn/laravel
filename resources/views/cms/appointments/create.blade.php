@extends('cms.parent')
@section('title', 'موعد جديد')
@section('page-title', 'إضافة موعد جديد')

@section('content')
<div class="card col-lg-6">
    <div class="card-body">
        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">التاريخ والوقت</label>
                <input type="datetime-local" name="appointment_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select" required>
                    <option value="مجدول">مجدول</option>
                    <option value="مكتمل">مكتمل</option>
                    <option value="ملغي">ملغي</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">المحامي</label>
                <select name="users_id" class="form-select" required>
                    <option value="">— اختر —</option>
                    @foreach ($lawyers as $lawyer)<option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>@endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">الموكل</label>
                <select name="clients_id" class="form-select" required>
                    <option value="">— اختر —</option>
                    @foreach ($clients as $client)<option value="{{ $client->id }}">{{ $client->user?->name }}</option>@endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">مرتبط بحجز (اختياري)</label>
                <select name="bookings_id" class="form-select">
                    <option value="">— بدون —</option>
                    @foreach ($bookings as $booking)<option value="{{ $booking->id }}">{{ $booking->visitor_name }} — {{ $booking->preferred_date->format('Y-m-d') }}</option>@endforeach
                </select>
            </div>
            <button class="btn btn-primary">حفظ</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
