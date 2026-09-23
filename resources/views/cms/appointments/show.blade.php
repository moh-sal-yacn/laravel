@extends('cms.parent')
@section('title', 'تفاصيل الموعد')
@section('page-title', 'تفاصيل الموعد')

@section('content')
<div class="card col-lg-6">
    <div class="card-header bg-white fw-bold">بيانات الموعد</div>
    <div class="card-body">
        <dl class="row mb-0 small">
            <dt class="col-5 text-muted">التاريخ والوقت</dt><dd class="col-7">{{ $appointment->appointment_date->format('Y-m-d H:i') }}</dd>
            <dt class="col-5 text-muted">الحالة</dt><dd class="col-7"><span class="badge text-bg-light">{{ $appointment->status }}</span></dd>
            <dt class="col-5 text-muted">المحامي</dt><dd class="col-7">{{ $appointment->lawyer?->name ?? '—' }}</dd>
            <dt class="col-5 text-muted">الموكل</dt><dd class="col-7">{{ $appointment->client?->user?->name ?? '—' }}</dd>
            <dt class="col-5 text-muted">الحجز المرتبط</dt><dd class="col-7">{{ $appointment->booking?->visitor_name ?? '—' }}</dd>
        </dl>
    </div>
    <div class="card-footer bg-white">
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> تعديل</a>
    </div>
</div>
@endsection
