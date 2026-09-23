@extends('cms.parent')
@section('title', 'تعديل حجز')
@section('page-title', 'تعديل طلب الحجز')

@section('content')
<div class="card col-lg-6">
    <div class="card-body">
        <form action="{{ route('bookings.update', $booking) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">اسم الزائر</label>
                <input type="text" name="visitor_name" class="form-control" value="{{ old('visitor_name', $booking->visitor_name) }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label class="form-label">نوع الحجز</label>
                <select name="booking_type" class="form-select" required>
                    @foreach (['استشارة', 'متابعة قضية', 'توقيع عقد', 'أخرى'] as $type)
                        <option value="{{ $type }}" @selected(old('booking_type', $booking->booking_type) === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">الموعد المفضل</label>
                <input type="datetime-local" name="preferred_date" class="form-control" value="{{ old('preferred_date', $booking->preferred_date->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select" required>
                    @foreach (['قيد الانتظار', 'مؤكد', 'ملغي', 'مكتمل'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $booking->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">المحامي</label>
                <select name="users_id" class="form-select">
                    <option value="">— بدون —</option>
                    @foreach ($lawyers as $lawyer)
                        <option value="{{ $lawyer->id }}" @selected(old('users_id', $booking->users_id) == $lawyer->id)>{{ $lawyer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">الموكل</label>
                <select name="clients_id" class="form-select">
                    <option value="">— بدون —</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('clients_id', $booking->clients_id) == $client->id)>{{ $client->user?->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
