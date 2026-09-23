@extends('cms.parent')
@section('title', 'حجز: '.$booking->visitor_name)
@section('page-title', 'تفاصيل الحجز')

@section('content')
<div class="row g-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-white fw-bold">بيانات الحجز</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">الزائر</dt><dd class="col-7">{{ $booking->visitor_name }}</dd>
                    <dt class="col-5 text-muted">النوع</dt><dd class="col-7">{{ $booking->booking_type }}</dd>
                    <dt class="col-5 text-muted">الموعد</dt><dd class="col-7">{{ $booking->preferred_date->format('Y-m-d H:i') }}</dd>
                    <dt class="col-5 text-muted">الحالة</dt><dd class="col-7"><span class="badge text-bg-light">{{ $booking->status }}</span></dd>
                    <dt class="col-5 text-muted">المحامي</dt><dd class="col-7">{{ $booking->lawyer?->name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">الموكل</dt><dd class="col-7">{{ $booking->client?->user?->name ?? '—' }}</dd>
                </dl>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> تعديل</a>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white fw-bold">المواعيد المرتبطة</div>
            <ul class="list-group list-group-flush">
                @forelse ($booking->appointments as $appointment)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $appointment->appointment_date->format('Y-m-d H:i') }}</span>
                        <span class="badge text-bg-light">{{ $appointment->status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">لا توجد مواعيد مرتبطة بهذا الحجز.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
