@extends('cms.parent')
@section('title', 'تعديل موعد')
@section('page-title', 'تعديل الموعد')

@section('content')
<div class="card col-lg-6">
    <div class="card-body">
        <form action="{{ route('appointments.update', $appointment) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">التاريخ والوقت</label>
                <input type="datetime-local" name="appointment_date" class="form-control" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select" required>
                    @foreach (['مجدول', 'مكتمل', 'ملغي'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $appointment->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">المحامي</label>
                <select name="users_id" class="form-select" required>
                    @foreach ($lawyers as $lawyer)
                        <option value="{{ $lawyer->id }}" @selected(old('users_id', $appointment->users_id) == $lawyer->id)>{{ $lawyer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">الموكل</label>
                <select name="clients_id" class="form-select" required>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('clients_id', $appointment->clients_id) == $client->id)>{{ $client->user?->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">مرتبط بحجز (اختياري)</label>
                <select name="bookings_id" class="form-select">
                    <option value="">— بدون —</option>
                    @foreach ($bookings as $booking)
                        <option value="{{ $booking->id }}" @selected(old('bookings_id', $appointment->bookings_id) == $booking->id)>{{ $booking->visitor_name }} — {{ $booking->preferred_date->format('Y-m-d') }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection
